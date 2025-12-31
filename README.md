# Webhole Homepage Manager

![Distribution: GitHub Only](https://img.shields.io/badge/Distribution-GitHub%20Only-black?style=for-the-badge&logo=github)
![Philosophy: Stability First](https://img.shields.io/badge/Philosophy-Stability%20First-0f172a?style=for-the-badge)

A lightweight WordPress plugin for maintenance mode and homepage replacement — without modifying your active theme.

Built for developers, sysadmins, and site owners who want **control without bloat**.

---

## ✨ Features

- 🔧 Enable / disable homepage override instantly
- 🧭 Multiple modes:
  - Default maintenance template
  - Existing WordPress page
- 👀 Secure preview mode (admin-only, nonce-protected)
- 🔁 Exit preview at any time
- 🧩 Works with any theme
- 🧼 No database clutter, no page builders, no shortcodes
- 📣 Optional developer announcements (remote message feed)

---

## 🧠 How It Works

When enabled, the plugin intercepts homepage requests and conditionally renders:

- A **clean default maintenance template**, or
- A **selected existing WordPress page**

Visitors see the maintenance page.  
Admins can preview safely without affecting the public site.

---

## ⚙️ Settings

Navigate to: WordPress Admin → Settings → Webhole Homepage Manager

Available options:

- Enable / Disable override
- Select mode (Default Template or Existing Page)
- Preview homepage override
- Exit preview
- Toggle developer announcements

---

## 🖼 Default Maintenance Template

The default template automatically displays:

- Site name
- Maintenance mode notice
- Clean, minimal layout
- Dynamic copyright footer

Example output:

> **Site Name**  
> This site is currently in maintenance mode.  
> Please check back soon.

---

## 📣 Developer Announcements

The plugin can optionally display announcements pulled from the Webhole
Admins may toggle this on or off from the settings page.

---

## 🔐 Security Notes

- Preview mode is protected by nonces
- Admin-only access
- No unauthenticated AJAX endpoints
- No external writes

---

## 🧪 Compatibility

- WordPress 6.x+
- PHP 8.0 – 8.3
- Apache / Nginx
- Docker & VPS environments tested

---

## 📂 Plugin Structure
webhole-homepage-manager/ ├── admin/ ├── assets/ ├── screenshots/ ├──
webhole-homepage-manager.php ├── README.md ├── CHANGELOG.md ├── LICENSE

---

## 🛠 Philosophy

This plugin was built with one goal:

> **Do one thing well. Stay out of the way.**

No tracking.
No upsells. 
No nonsense.

---

## 👤 Author

**Clifford Webhole** 
https://cliffordswebhole.com
https://github.com/cliffordwebhole
---

## 📄 License

MIT License — see `LICENSE` file for details.
