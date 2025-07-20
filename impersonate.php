<?php
/*
 * Plugin Name: Impersonate
 * Plugin URI:  https://github.com/michaelspatrick/admin_impersonate
 * Description: Allows administrators to impersonate another user securely for testing and support.
 * Version:     1.0
 * Author:      Michael Patrick
 * License:     GPLv2 or later
 */

if (!defined('ABSPATH')) exit;

// Safe session start
function ai_start_session_if_needed() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
add_action('init', 'ai_start_session_if_needed', 1);

// Restore original admin on logout
function ai_end_impersonation() {
    if (!empty($_SESSION['ai_original_user'])) {
        $original_id = intval($_SESSION['ai_original_user']);
        unset($_SESSION['ai_original_user']);
        wp_set_auth_cookie($original_id);
        wp_redirect(admin_url());
        exit;
    }
}
add_action('wp_logout', 'ai_end_impersonation', 1);

// Register admin-only features after plugins and users are loaded
add_action('plugins_loaded', 'ai_register_admin_hooks');
function ai_register_admin_hooks() {
    // Run only in admin dashboard and only for admins
    if (!is_admin()) return;

    add_filter('manage_users_columns', 'ai_add_user_column');
    add_filter('manage_users_custom_column', 'ai_render_user_column', 10, 3);
    add_action('admin_init', 'ai_handle_impersonation_request');
}

// Add "Impersonate" column
function ai_add_user_column($columns) {
    $columns['admin_impersonate'] = 'Impersonate';
    return $columns;
}

// Show impersonate button
function ai_render_user_column($value, $column_name, $user_id) {
    if ($column_name === 'admin_impersonate' && current_user_can('manage_options')) {
        $url = wp_nonce_url(admin_url('?impersonate_user=' . $user_id), 'impersonate_user_' . $user_id);
        return '<a class="button" href="' . esc_url($url) . '">Impersonate</a>';
    }
    return $value;
}

// Handle impersonation
function ai_handle_impersonation_request() {
    if (!current_user_can('manage_options')) return;

    if (isset($_GET['impersonate_user'])) {
        $user_id = intval($_GET['impersonate_user']);
        $nonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';

        if (!wp_verify_nonce($nonce, 'impersonate_user_' . $user_id)) {
            wp_die('Invalid impersonation request.');
        }

        $_SESSION['ai_original_user'] = get_current_user_id();
        wp_set_auth_cookie($user_id);
        wp_redirect(admin_url());
        exit;
    }
}

