---
layout: default
title: "React.js"
nav_order: 21
has_children: true
---

# React.js
## Table of contents
1. [Overview](#overview)
2. [Vite Setup](#vite-setup)
3. [File Structure & Naming](#file-structure)

<br>

## 1. Overview <a id="overview"></a>
This framework natively supports React.js views along with any supporting components and utilities.

<br>

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

## 2. Vite Setup <a id="vite-setup"></a>
All npm packages needed for React are automatically installed when you create a new project.

<br>

**Development**

Start the development server by running the following command:
```bash
npm run dev
```

Make sure the `APP_ENV` environment variable is set to `local`.  If you are using the PHP standalone the `APP_DOMAIN` environment variable should be set to `'http://localhost:8000/'`.

Running above command in Windows PowerShell can cause an issue.  Make sure to run with Command Prompt.

<br>

**How the Framework Finds the Vite Server**

While `npm run dev` is running, the `chappyHot` Vite plugin writes the dev server's URL to a file called `public/hot`.  The framework reads this file to decide which assets to use:

| `APP_ENV` | `public/hot` exists? | Assets used |
|---|---|---|
| `local`, `dev`, or `development` | Yes | Vite dev server (hot module replacement) |
| `local`, `dev`, or `development` | No | Build output in `public/build` |
| Any other value | Either | Build output in `public/build` |

The `public/hot` file is removed when you stop the dev server with `Ctrl+C` and when you run `npm run build`.  It is listed in `.gitignore` and should never be committed.

If you are in a dev environment without the Vite server running, run `npm run build` at least once.  Otherwise there are no assets for your React views to load.

<br>

**Developing in a VM or Container**

By default the dev server URL is `http://localhost:5173`.  This works when the browser and the Vite server run on the same machine.  When your project runs in a VM or container and you browse from the host, `localhost` points to the host instead.  Set `VITE_DEV_SERVER` in your `.env` file to the address the browser uses to reach the VM:
```bash
VITE_DEV_SERVER='http://my-vm.test:5173'
```

When the hostname is not `localhost`, Vite listens on all interfaces (`0.0.0.0`) so the host can reach it.  Make sure port `5173` is open on the VM:
```bash
sudo ufw allow 5173/tcp
```

`VITE_DEV_SERVER` is resolved in the following order:
1. The contents of `public/hot` while `npm run dev` is running
2. `VITE_DEV_SERVER` in your `.env` file
3. `http://localhost:5173`

**Note:** A `VITE_DEV_SERVER` variable set in your shell environment overrides the value in `.env`.  If the wrong URL appears in `public/hot`, check `env | grep VITE_DEV_SERVER`.

<br>

**Pre-Production**

Test your app in pre-production with the PHP standalone server by running the following command:
```bash
npm run build
```

Set the `APP_ENV` environment variable to `production`, or stop the Vite server.  Your React.js views will now render without using the Vite server.

Running above command in Windows PowerShell can cause an issue.  Make sure to run with Command Prompt.

<br>

**Production**

When configuring your Apache or Nginx server make sure the `APP_DOMAIN` environment variable is set to `/`.  Run `npm run build` during deployment and do not deploy a `public/hot` file.

<br>

**Troubleshooting**

- **React views render the NotFound page or don't load:** Check the page source.  If scripts load from a dev server URL while Vite is not running, delete `public/hot`.  This can happen if the dev server was killed without shutting down cleanly.  Running `npm run build` also removes it.
- **Scripts load from the wrong host:** Check the contents of `public/hot` and the `VITE_DEV_SERVER` value in your `.env` file.
- **Browser can't reach the dev server from the host:** Verify that port `5173` is open on the VM and that Vite reports a `Network:` address when it starts.

<br>

## 3. File Structure & Naming <a id="file-structure"></a>
- Pages: `resources/js/pages/<area>/<Page>.jsx`
- PHP can pass names like h`ome.index`, `home/Index`, or `admin/Users.index`.
- Components: `resources/js/components/*`
- Utils: `resources/js/utils/*`
- Core package (vendor): `vendor/chappy-php/chappy-php-framework/src/React/*` (reusable helpers/components)