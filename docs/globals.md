<h1 style="font-size: 50px; text-align: center;">Global Helpers</h1>

## Table of contents
1. [Overview](#overview)
2. [Globals](#globals)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework comes with a collection of global helpers to facilitate commonly performed tasks.  Many are wrapper functions for those that are provided by various classes.

<br>

## 2. Globals <a id="globals"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

### A. `asset()`
**Description:** Returns the full public URL for a given asset.
```php
asset('images/logo.png');
// Outputs: http://yourdomain.com/images/logo.png
```

<br>

### B. `cl()`
**Description:** Prints one or more variables to the browser console via JavaScript.
```php
cl($user, $post);
```

<br>

### C. `config()`
**Description:** Retrieves a configuration value using dot notation.
```php
config('app.debug');
```

<br>

### D. `dd()`
**Description:** Dumps variables using Symfony's VarDumper and halts execution.

<br>

### E. `dump()`
**Description:** Dumps variables using Symfony's VarDumper without stopping execution.
```php
dump($request);
```

<br>

### F. `e()`
**Description:** Escapes a string for safe HTML output.
```php
<h1>Welcome, <?= e($user['fname']) ?>!</h1>
```

<br>

### G. `flashMessage()`
**Description:** Adds a flash message to the session.
```php
flashMessage(Session::SUCCESS, 'User created successfully.');
```

<br>

### H. `info()`
**Description** Generates output messages for console commands.  A wrapper for the [Session::info() function](console#info).
```php
info("My message", Logger::INFO, Tools::BG_GREEN, Tools::TEXT_LIGHT_GREY);
```

<br>

### I. `logger()`
**Description:** Writes a message to the log file with a specified level.
```php
logger('User login failed', 'error');
```

<br>

### J. `now()`
**Description:** Returns the current time formatted using application or user preferences.
```php
now();
now('Europe/Berlin', 'H:i', 'de');
```

<br>

### K. `redirect()`
**Description:** Redirects to a specified route.
```php
redirect('login');
redirect('user.profile', [42]);
```

<br>

### L. `route()`
**Description:** Generates a route URL from a dot-notated path and optional parameters.
```php
route('user.profile', [42]);
```

<br>

### M. `vite()`
**Description:** Returns the correct URL for a Vite-managed frontend asset.
```php
vite('resources/js/app.js');
```