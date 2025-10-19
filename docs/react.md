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
    - richer, interactive UI via React while keeping your PHP framework’s routing, ACL, validation, and controllers.
    - Support this feature natively so user does no have to spend time setting up React.
    - Support an environment where PHP and React views coexist within the same project.

- Architecture:
    - PHP renders a host `<div id="app" data-component="Home" data-props="…">`
    - A single Vite entry (`app.jsx`) dynamically imports pages using `import.meta.glob`
    - Controller helper `renderJsx('area.Page', $props)` chooses which React page to mount

<br>

## 2. Vite Setup <a id="vite-setup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
All npm packages needed for React are automatically installed when you create a new project.

**Production**: build with `npm run build` and use your PHP helper (`Vite::tags(...)`) to load hashed assets from `public/build/manifest.json`.

<br>

## 3. File Structure & Naming <a id="file-structure"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- Pages: `resources/js/pages/<area>/<Page>.jsx`
- PHP can pass names like h`ome.index`, `home/Index`, or `admin/Users.index`.
- Components: `resources/js/components/*`
- Utils: `resources/js/utils/*`
- Core package (vendor): `vendor/chappy-php/chappy-php-framework/src/React/*` (reusable helpers/components)