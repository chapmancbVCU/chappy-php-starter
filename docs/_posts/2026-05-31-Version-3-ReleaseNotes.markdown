---
layout: post
title:  "Version 3.0 Release Notes!"
date:   2026-05-30 15:00:00 -0500
categories: release
---
---

🎉 **Version 2.0 of the Chappy PHP Framework is here!**

This is the most feature-packed release to date, introducing an upgrade to the form validation system.  The original form validation system does not work with this version.  Consult the user guide on how to make the appropriate changes.

---

## 🚀 New Features and Updates

- Resolved dompurify not found issue when using latest version in starter project
- Installed version 3.4.1 for dompurify in starter project
- Resolved duplicate "include" key in optimizeDeps object in `vite.config.js`
- Resolved issue where `baseUrl` is depreciated in Vite when declared in `jsconfig.json`
- Fixed return type for `csrf()` global function to be string instead of void
- Added COUNTRY_CODE to `.env` file for phone number validation
- Added unique and tel validators to Has Validators trait
- Resolved issue where E-mail attachments failed to upload

---

<br>

## 📘 Documentation

Everything is documented in the user guide:  
👉 [https://chapmancbvcu.github.io/chappy-php-starter/](https://chapmancbvcu.github.io/chappy-php-starter/)

<br>

---

Thank you to everyone testing, building, and exploring Chappy v3!