<h1 style="font-size: 50px; text-align: center;">Global Helpers</h1>

## Table of contents
1. [Overview](#overview)
2. [Globals](#globals)
3. [Forms](#forms)

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

### F. `flashMessage()`
**Description:** Adds a flash message to the session.
```php
flashMessage('success', 'User created successfully.');
```

<br>

### G. `logger()`
**Description:** Writes a message to the log file with a specified level.
```php
logger('User login failed', 'error');
```

<br>

### H. `now()`
**Description:** Returns the current time formatted using application or user preferences.
```php
now();
now('Europe/Berlin', 'H:i', 'de');
```

<br>

### I. `redirect()`
**Description:** Redirects to a specified route.
```php
redirect('login');
redirect('user.profile', [42]);
```

<br>

### J. `route()`
**Description:** Generates a route URL from a dot-notated path and optional parameters.
```php
route('user.profile', [42]);
```

<br>

### K. `vite()`
**Description:** Returns the correct URL for a Vite-managed frontend asset.
```php
vite('resources/js/app.js');
```

<br>

## 3. Forms <a id="forms"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
These helpers are wrappers around the Core\FormHelper class and are intended to simplify form building and validation handling in your views.

### A. `checkboxLabelLeft()`
**Description:** Generates a checkbox input with the label placed to the left.
```php
checkboxLabelLeft($label, $name, $value = '', $checked = false, $inputAttrs = [], $divAttrs = [], $errors = []);
```

<br>

### B. `checkboxLabelRight()`
**Description:** Generates a checkbox input with the label placed to the right.
```php
checkboxLabelRight($label, $name, $value = '', $checked = false, $inputAttrs = [], $divAttrs = [], $errors = []);
```

<br>

### C. `csrf()`
**Description:** Renders a CSRF hidden input field.
```php
csrf()
```

<br>

### D. `errorBag()`
**Description:** Displays a list of form validation errors.
```php
errorBag($errors);
```

<br>

### E. `email()`
**Description:** Renders a labeled input of type `email`.
```php
email($label, $name, $value = '', $inputAttrs = [], $divAttrs = [], $errors = []);
```

<br>

### F. `hidden()`
**Description:** Renders an input of type `hidden`.
```php
hidden($name, $value);
```

<br>

### G. `input()`
**Description:** Generic input block for types other than submit. Use for `text`, `password`, `number`, etc.
```php
input($type, $label, $name, $value = '', $inputAttrs = [], $divAttrs = [], $errors = []);
```

### H. `output()`
**Description:** Renders an `<output>` HTML element.
```php
output($name, $for);
```

<br>

### I. `radio()`
**Description:** Renders an input of type `radio` with label.
```php
radio($label, $id, $name, $value, $checked = false, $inputAttrs = []);
```

<br>

### J. `select()`
**Description:** Renders a `<select>` dropdown with options.
```php
select($label, $name, $value, $options, $inputAttrs = [], $divAttrs = [], $errors = []);
```

<br>

### K. `submitBlock()`
**Description:** Renders a submit button inside a surrounding div.
```php
submitBlock($buttonText, $inputAttrs = [], $divAttrs = []);
```

<br>

### L. `submit()`
**Description:** Renders a standalone submit button.
```php
submit($buttonText, $inputAttrs = []);
```

<br>

### M. `tel()`
**Description:** Renders a labeled input of type `tel` (telephone).
```php
tel($label, $name, $value = '', $inputAttrs = [], $divAttrs = [], $errors = []);
```

### N. `textarea()`
**Description:** Renders a `<textarea>` block with label.
```php
textarea($label, $name, $value = '', $inputAttrs = [], $divAttrs = [], $errors = []);
```