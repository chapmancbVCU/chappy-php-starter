---
layout: post
title:  "Version 2.2.0 Release Notes!"
date:   2026-04-11 12:00:00 -0500
categories: release
---
---

🎉 **Version 2.2.0 of the Chappy PHP Framework is here!**

This is the most feature-packed release to date, introducing updates for seeders and new factory support..

---

## 🚀 New Features

- Added wrapper class for questions
- Added chainable validators for CLI input
- A console class with the following
    - `HasValidators` trait to make validators easily accessible to command helpers
    - Validator and wrapper for `FrameworkQuestion` ask function
- Improved workflow for commands in make workspace
- Resolved issue where rules for password complexity were not being rendered correctly based on values in .env file
- Resolved typing issues with `notifications:test` command
- Resolved issue where `notifications:test` fails when user `id/name/email` is not provided
- Reduced number of parameters needed to call functions within TestRunner parent class by using constants for test suites and supported file extensions.
- Made `testByFilter` available for child classes of `TestRunner` class.
- Added prompt asking user to confirm before performing migrate operations in production.
- Documentation standardization improvements for describing functions and formatting of overall content.

---

<br>

## 📘 Documentation

Everything is documented in the user guide:  
👉 [https://chapmancbvcu.github.io/chappy-php-starter/](https://chapmancbvcu.github.io/chappy-php-starter/)

<br>

---

Thank you to everyone testing, building, and exploring Chappy v2.2.0!