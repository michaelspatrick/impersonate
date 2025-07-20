# Impersonate Plugin for WordPress

**Impersonate** is a lightweight and secure plugin that allows WordPress administrators to temporarily log in as any other user for support, testing, or troubleshooting purposes. This is especially useful for debugging user-specific issues or verifying access controls without resetting passwords.

## 🔧 Features

- One-click impersonation from the Users table
- Secure impersonation via WordPress nonces
- Automatically restores admin session on logout
- Supports PHP 8.3+ and latest WordPress versions
- Session-based tracking of impersonation status

## ✅ Requirements

- WordPress 5.5+
- PHP 7.4 or higher (fully compatible with PHP 8.3+)
- Admin privileges (`manage_options` capability)

## 🚀 Installation

1. Download or clone this repository.
2. Upload the `admin-impersonate` folder to your `/wp-content/plugins/` directory.
3. Activate the plugin through the WordPress admin dashboard.
4. Go to **Users > All Users**, and click the “Impersonate” button next to any user.

## 🔒 Security Notes

- Only users with the `manage_options` capability can impersonate others.
- All impersonation requests are protected using WordPress nonces.
- The original admin user is stored in the session and restored upon logout.

## 📜 License

This plugin is licensed under the [GPLv2 or later](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html).

## ✍️ Author

Developed by **Michael Patrick** for use with [Dragon Society International](https://dragonsociety.com)
