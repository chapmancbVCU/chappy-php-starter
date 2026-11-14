---
layout: post
title:  "Version 4.0 Release Notes!"
date:   2026-11-14 12:00:00 -0500
categories: release
---
---

🎉 **Version 2.0 of the Chappy PHP Framework is here!**

### Added
- Session Storage and Local Storage API
- JavaScript support for DateTime features
- `JsonSerializable` support in the `Model` class via `jsonSerialize()`, so you can choose which data is hidden or exposed in React.js props
- PHPStan support

### Improved
- Pagination now supports filtering, different content presentation types, and React.js integration
- Carbon integration for DateTime handling across the framework

### Changed
- Moved request initialization and handling, including `session_start` and other init functions, from `index.php` to `core/Application`
- Moved all Console stub files to the new `Console\Helpers\Stubs` directory

### Fixed
- Menu in mobile mode
- Set password and activate/inactivate workflows failing with `Invalid datetime format: 1366 Incorrect integer value: 'on' for column 'mvc'.'users'.'<field_name>' at row 1`
- Reset password form reporting that the username already exists on submit
- `react:auth` command not generating the `ResetPassword.jsx` view
- `make:view` command applying `ucfirst` to the directory and view names
- Seeder command validating option input when the option isn't provided
- Build failures caused by `dompurify`
- Database connection using `root` instead of the username set in the `database.php` config file

### Security
- Prevented Cross-Site Scripting (XSS) when calling the `setSiteTitle`, `asset`, and `route` global functions

### Documentation
- Updated documentation for project setup and app deployment
- Added a search feature to the user guide

---

<br>

## 📘 Documentation

Everything is documented in the user guide:  
👉 [https://chapmancbvcu.github.io/chappy-php-starter/](https://chapmancbvcu.github.io/chappy-php-starter/)

<br>

---

Thank you to everyone testing, building, and exploring Chappy v3!