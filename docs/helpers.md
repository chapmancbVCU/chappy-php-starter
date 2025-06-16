<h1 style="font-size: 50px; text-align: center;">Helper Class</h1>

## Table of contents
1. [Overview](#overview)
2. [buildMenuListItems](#menu-list)
3. [currentPage](#current-page)
4. [getObjectProperties](#obj-properties)
5. [getProfileImage](#get-profile-image)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The Helper class contains globally accessible utility functions to support user interface building, user profile lookups, and object handling. These methods are statically accessible and simplify various common tasks.  This class is part of the `Core` namespace.

<br>

## 2. `buildMenuListItems(array $menu, string $dropdownClass = "")` <a id="menu-list"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Dynamically builds a Bootstrap-friendly navigation menu based on an associative array. Dropdowns, active states, and logout buttons are supported.

Parameters:
- `array $menu` — The list of menu items (`label => path` pairs or nested arrays for dropdowns)
- `string $dropdownClass` — Optional class for styling dropdown menus

Returns:
- A string of HTML list elements (wrapped in `<li>` tags), or `false` if output buffering fails

<br>

## 3. `currentPage()` <a id="current-page"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns the current URL path based on the request URI, normalized for comparison in menu rendering.

Returns:
- `string` — The current URL path, excluding domain

Note: Automatically treats `/`, `/home/index`, and app root as equivalent to `/home`.

<br>

## 4. `getObjectProperties(object $object)` <a id="obj-properties"></a><<span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns a list of accessible (non-static) properties and their values from the given object.

Parameters:
- `object $object` — The object whose properties will be listed

Returns:
- `array` — Associative array of properties and values

```php
$userProps = Helper::getObjectProperties($user);
```

<br>

## 5. `getProfileImage()` <a id="get-profile-image"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns the profile image for the currently logged-in user by querying the `ProfileImages` model.

Returns:
- `ProfileImages|null|false` — A profile image object if found; `null` or `false` otherwise

