<h1 style="font-size: 50px; text-align: center;">Java Script and Vite</h1>

## Table of contents
1. [Overview](#overview)
2. [app.js](#app_js)
3. [Vite Asset Bundling](#vite-asset-bundling)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework comes with some built in JavaScrip files to support basic tasks as described below:
1. Front end password match validation
2. Phone number format validation
3. TinyMCE support.

These scripts can be imported into any view that needs these features.  They can be found at resources/js.
<br>

## 2. app.js <a id="app_js"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Additional JavaScript features can also be included in this framework.  The entry point for additional JavaScript can be found at resources/js/app.js
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