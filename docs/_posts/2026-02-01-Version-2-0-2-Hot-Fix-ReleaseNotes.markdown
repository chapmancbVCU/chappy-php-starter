---
layout: post
title:  "Version 2.0.2 Release Notes!"
date:   2026-02-01 20:25:00 -0500
categories: hotfix
---
Performed the following updates:
- Made frequency of logging configurable based on severity level
- Made frequency of console logging configurable based on severity level
- Created global helpers for logging based on severity level
- Created global helpers for console logging based on severity level
- Updated documentation to reflect updates
- Fixed binding issue in tableExists function in DB class
- Resolved issue where sensitive data can be written to log
- Added section to Config and Env Classes to describe configuration file
- Improved workflow of messaging when incorrect logging level is provided to Logger::log()
- Improved workflow of messaging when incorrect logging level is provided to Tools::info()
- Improved workflow of messaging when invalid background and/or text color is provided to Tools::info()
- Moved all Console related features in Tools class to ConsoleLogger class
- Updated api docs to reflect changes

---

<br>

Update to config:

- Set the `LOGGING` variable to a value based on PSR-3 severity levels
- Add below `DB_PASSWORD` the `DB_LOG_PARAMS` variable and set it to `full`, `masked`, or `none`
- In `config\config.php` ensure the array element looks as follows: `logging' => Env::get('LOGGING'),`
- In `config\database.php` add the following: `db_log_params' => Env::get('DB_LOG_PARAMS' ?? 'none'),`

---

<br>

Other updates:

For all calls to `Tools::info` use new globals or change call to `ConsoleLogger::log`.  Any constants passed used formerly by the `Tools` class should be changed to `ConsoleLogger`.