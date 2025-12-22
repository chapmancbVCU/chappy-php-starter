<h1 style="font-size: 50px; text-align: center;">Using APIs</h1>

## Table of contents
1. [Overview](#overview)
2. [api.js](#api-js)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This guide provides a detailed overview for the everything needed to user APIs with this framework.

The two main components:
- api.js - A utility available for use with JavaScript to perform API related tasks.
- JsonResponse.php - A trait available for use by your controller classes to support API related tasks.

<br>

## 2. api.js <a id="api-js"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This file contains all of the API utilities needed to perform operations with JavaScript.  This file provides two things:

1. **A small HTTP client** built on `fetch()` (`apiRequest`) with convenience wrappers (`apiGet`, `apiPost`, etc.).
2. **A React hook** (`useAsync`) that runs async work and manages `{ data, loading, error }`, with built-in cancellation to prevent stale updates.

These utilities are intended for **same-origin** API calls (your Chappy.php app and its API endpoints), and they work with CSRF protection and cookie-base authentication.

