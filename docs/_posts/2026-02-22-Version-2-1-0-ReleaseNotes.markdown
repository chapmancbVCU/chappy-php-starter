---
layout: post
title:  "Version 2.1.0 Release Notes!"
date:   2026-02-22 14:53:00 -0500
categories: release
---
---

🎉 **Version 2.1.0 of the Chappy PHP Framework is here!**

This is the most feature-packed release to date, introducing updates for seeders and new factory support..

---

## 🚀 New Features

- Updated naming standards for migration so names are user friendly
- Resolved issue in `AuthService::currentUser()` where null is returned on Windows when user runs migration while user is logged in with remember me cookie
- Updated naming convention for migration to contain table name and function (create, update, rename)
- Added command to restore built-in migrations using specific flag or no flag for all
- Fixed broken links in forms.md page
- In main_menu made `DEFAULT_CONTROLLER` as value for route for brand link
- Updated make:menu template to use `DEFAULT_CONTROLLER` as value for out for brand link
- Updated make:menu template to use `env` globals instead of `Env:get()`
- Made tinker be installed when project is created
- Reduced number of parameters needed to call `Uploads::handleUpload` function
- Created new `count()` function in Model class that is a wrapper for the DB::count function
- Added support for database factories
- Added `--seeder` flag to allow users to specify name of specific seeder class when seeding a database
- Added `--seeder` flag to `migrate`, `migrate:refresh`, and `migrate:fresh` commands
- Added `--factory` flag to make:seeder command to all user to create both a factory and seeder at the same time

---

<br>

## 📘 Documentation

Everything is documented in the user guide:  
👉 [https://chapmancbvcu.github.io/chappy-php-starter/](https://chapmancbvcu.github.io/chappy-php-starter/)

<br>

---

Thank you to everyone testing, building, and exploring Chappy v2.1.0!