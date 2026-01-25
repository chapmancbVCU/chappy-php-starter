<h1 style="font-size: 50px; text-align: center;">React.js</h1>

## Table of contents
1. [Overview](#overview)
2. [Vite Setup](#vite-setup)
3. [File Structure & Naming](#file-structure)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework natively supports React.js views along with any supporting components and utilities.

**Goals & Architecture**
- Goal: 
    - Richer, interactive UI via React while keeping this framework’s routing, ACL, validation, and controllers.
    - Support this feature natively so user does no have to spend time setting up React.
    - Support an environment where PHP and React views coexist within the same project.

- Architecture:
    - PHP renders a host `<div id="app" data-component="Home" data-props="…">`
    - A single Vite entry (`app.jsx`) dynamically imports pages using `import.meta.glob`
    - Controller helper `renderJsx('area.Page', $props)` chooses which React page to mount

<br>

## 2. Vite Setup <a id="vite-setup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
All npm packages needed for React are automatically installed when you create a new project.

**Development**

Start the development server by running the following command:
```bash
npm run dev
```

Make sure the `APP_ENV` environment variable is set to `local`.  If you are using the PHP standalone the `APP_DOMAIN` environment variable should be set to `'http://localhost:8000/'`.

Running above command in Windows PowerShell can cause an issue.  Make sure to run with Command Prompt.

**Pre-Production**

Test your app in pre-production with the PHP standalone server by running the following command:
```bash
npm run build
```

Set the `APP_ENV` environment variable to `production`.  Make sure the Vite server is not running.  Your React.js views will now render without using the Vite server.

Running above command in Windows PowerShell can cause an issue.  Make sure to run with Command Prompt.

**Production**

When configuring your Apache or Nginx server make sure the `APP_DOMAIN` environment variable is set to `/`.

<br>

## 3. File Structure & Naming <a id="file-structure"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- Pages: `resources/js/pages/<area>/<Page>.jsx`
- PHP can pass names like h`ome.index`, `home/Index`, or `admin/Users.index`.
- Components: `resources/js/components/*`
- Utils: `resources/js/utils/*`
- Core package (vendor): `vendor/chappy-php/chappy-php-framework/src/React/*` (reusable helpers/components)