---
layout: post
title:  "Version 4.1.0 Release Notes!"
date:   2026-11-22 18:00:00 -0500
categories: release
---
---

🎉 **Version 4.1.0 of the Chappy PHP Framework is here!**

### Added
- `chappyHot` plugin to derive host, port, origin, and HMR host from VITE_DEV_SERVER
- `profileImg` helper functions to get profile image based on `PROFILE_IMAGE_DIRECTORY` and `VITE_PROFILE_IMAGE_DIRECTORY` variables

### Improved
- Moved where User Guide link is rendered in menu so that Admin link and menu is in same place for `admin` layout.
- Made `ProfileImages` model class' instance variables all strictly typed

### Changed
- Project logo is now referenced with `env` function so that changes to `S3_BUCKET` does not produce a broken link.

### Fixed
- Template stubs for composer local and remote templates for developers

---

<br>

## 📘 Documentation

Everything is documented in the user guide:  
👉 [https://chapmancbvcu.github.io/chappy-php-starter/](https://chapmancbvcu.github.io/chappy-php-starter/)

<br>

---

Thank you to everyone testing, building, and exploring Chappy v4!