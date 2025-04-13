<h1 style="font-size: 50px; text-align: center;">JavaScript and Vite</h1>

## Table of contents
1. [Overview](#overview)
2. [app.js](#app_js)
3. [Vite Asset Bundling](#vite-asset-bundling)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework includes first-class JavaScript support using [Vite](https://vitejs.dev/), enabling you to write modular, modern JavaScript while enjoying fast development builds and efficient production asset bundling.

The `resources/js` directory includes helpful built-in utilities and serves as your starting point for writing and organizing all JavaScript logic.

Common use cases already supported include:
- ✅ Front-end password confirmation validation  
- ✅ Phone number formatting helpers  
- ✅ TinyMCE rich text editor configuration

These can be imported directly in `app.js` or included via your views.

<br>

## 2. app.js <a id="app_js"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The entry point for your custom JavaScript is located at:
- resources/js/app.js

Use this file to:
- Import utility functions or packages
- Initialize form validation
- Configure TinyMCE
- Write view-specific scripts (e.g., document ready)

### ✳️ Example
```js
import './phoneFormat.js';
import './passwordMatch.js';

document.addEventListener('DOMContentLoaded', () => {
  console.log('App.js loaded');

  // Example: Hook into a custom button
  const button = document.querySelector('#myButton');
  if (button) {
    button.addEventListener('click', () => alert('Clicked!'));
  }
});
```

You can split your code into modules and import them here. Vite will compile it all into a single optimized bundle.

<br>

## 3. Vite Asset Bundling <a id="vite-asset-bundling"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework includes the ability to perform Vite asset bundling.  Run the following command to bundle your assets:

```sh
npm run build
```

Vite is also used to provide live updates of your views after saving a view file.  Run the following command to use this feature:

```sh
npm run dev
```

After running the command the npm based Vite server is started just like any Laravel or React.js based project.