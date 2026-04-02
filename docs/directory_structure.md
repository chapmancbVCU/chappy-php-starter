<h1 style="font-size: 50px; text-align: center;">Directory Structure</h1>

## Table of contents
1. [Overview](#overview)
2. [Directory Descriptions](#descriptions)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This is the standard structure of a Chappy.php project. It follows a clean and modular layout to support MVC architecture, console commands, configuration, views, and static assets.

```bash
.
├── app
│   ├── acl.json
│   ├── admin_menu_acl.json
│   ├── Controllers
│   ├── menu_acl.json
│   ├── Models
│   └── user_menu.json
├── cache
│   └── phpunit
├── composer.json
├── composer_local.txt
├── composer.lock
├── composer_remote.txt
├── config
├── console
├── database
│   ├── database.sqlite
│   ├── migrations
│   └── seeders
├── docs
├── index.php
├── jsconfig.json
├── LICENSE
├── :memory:
├── node_modules
├── package.json
├── package-lock.json
├── phpunit.xml
├── public
├── README.md
── resources
│   ├── css
│   ├── js
│   └── views
├── server.php
├── setup.js
├── storage
│   ├── app
│   └── logs
├── tests
│   ├── Feature
│   └── Unit
└── vite.config.js
```

<br>

## 2. Directory Descriptions <a id="descriptions"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
`/app`
Contains your core application logic:
- `Controllers/` – Handle HTTP requests and business logic.
- `Models/` – Represent and interact with database tables.
- `CustomValidators/` – Custom form and data validators.
- `Lib/` – Shared libraries and helpers (e.g. console tools).
- `*.json` – Access control configuration for dynamic menus and permissions.

<br>

`/config`
- Holds framework and application config files such as database settings, session handling, and console definitions.

<br>

`/console`
- Entry point for custom CLI commands using Chappy.php's built-in console interface.

<br>

`/database`
- Includes the SQLite file, migration scripts, and seeders used to set up and populate the database.

<br>

`/docs`
- User-facing documentation, including Jekyll-based site content for GitHub Pages.

<br>

`/public`
- The web server root. Contains front-end assets like logos and any public files accessible via URL.

<br>

`/resources`
Framework-managed frontend resources:
- `css/` – App-specific stylesheets.
- `js/` – JavaScript files and frontend utilities.
- `views/` – PHP views organized by section (e.g., layouts/, components/).

<br>

`/storage`
Used to store uploaded files and logs:
- `app/` – For private and public file uploads.
- `logs/` – Application and CLI logs.

<br>

`/tests`
- Contains PHP unit test classes.

<br>

`/index.php`
- Main entry point for the application, invoked by the web server.

<br>

`/server.php`
- Used by the built-in PHP development server.

<br>

`composer.json` / `package.json`
- Define PHP and JavaScript dependencies respectively.