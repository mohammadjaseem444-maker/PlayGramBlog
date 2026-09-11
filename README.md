# PlayGramBlog

A PHP 8+ / MySQL blogging CMS with public site and responsive admin panel.

## Install
1. Create a MySQL database/user.
2. Import `database/playgramblog.sql`.
3. Copy `config/config.example.php` to `config/config.php` and fill credentials, **or** use `/install/` once.
4. Ensure `assets/uploads/` is writable by PHP.
5. Visit `/admin/login.php`.
6. If you used the installer, delete/protect `/install/` after setup.

## Security notes
- PDO prepared statements.
- Passwords use `password_hash/password_verify`.
- CSRF protection on state-changing forms.
- Output escaping on user-controlled text.
- Image uploads are MIME-validated and stored with random names.
- Admin pages enforce role checks.

## Production
Use HTTPS, secure PHP/session cookie settings at the hosting layer, disable display_errors, and keep database credentials outside the public web root where possible.

## Important content-editor note
The post editor intentionally uses a textarea rather than shipping a third-party JavaScript editor. Published post HTML is rendered as HTML for rich articles, so only trusted editor/admin roles should publish raw HTML. If untrusted authors will be enabled, add an HTML allowlist sanitizer before publishing.
