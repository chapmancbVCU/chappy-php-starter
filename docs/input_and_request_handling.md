<h1 style="font-size: 50px; text-align: center;">Input and Request Handling</h1>

## Table of contents
1. [Overview](#overview)
2. [Getting Input](#getting-input)
3. [Detecting Request Type](#detecting-request-type)
4. [CSRF Token Check](#csrf-check)
5. [Full Method Reference](#reference)
6. [See Also](#see-also)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `Input` class is the main interface for retrieving and sanitizing user input in Chappy.php. It abstracts away access to `$_GET`, `$_POST`, and `$_REQUEST`, and ensures all input values are properly sanitized before use.

This class is automatically available in controllers as `$this->request`.

<br>

## 2. Getting Input <a id="getting-input"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. Get a Single Input Value
You can assign individual fields from your form to a variable or a field within your model.
```php
$user->email = $this->request->get('email');
```

If the input field is an array (e.g., checkbox group), it will be recursively sanitized.

<br>

### B. Get All Input Values
This returns all values from the request, sanitized as an associative array.
```php
$data = $this->request->get();
```

<br>

### C. Assigning Input to a Model
You can directly assign all request inputs to a model instance:
```php
$user = new Users();
$user->assign($this->request->get(), Users::blackListedFormKeys);
```

The second parameter allows you to skip assigning sensitive fields like passwords, tokens, or role IDs.

<br>

## 3. Detecting Request Type <a id="detecting-request-type"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
You can detect which HTTP method was used in the request using these methods:
```php
$this->request->isGet();    // true if GET request
$this->request->isPost();   // true if POST request
$this->request->isPut();    // true if PUT request
```

These checks rely on the REQUEST_METHOD server variable and are helpful when building APIs or advanced form workflows.

<br>

## 4. CSRF Token Check <a id="csrf-check"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
While CSRF tokens are handled by the `FormHelper` class, the `Input` class provides the means to check the validity of your token after form submit inside controller action functions:
```php
$this->request->csrfCheck();
```

If the token is invalid or missing, the user is redirected to the restricted token error page:
```bash
/restricted/badToken
```

<br>

## 5. Full Method Reference <a id="reference"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

| Method | Description |
|:------:|-------------|
| `get($key = null)` | Returns a single sanitized input value or all request inputs as an array |
| `getRequestMethod()` | Returns the HTTP method in uppercase (e.g., `GET`, `POST`, `PUT`) |
| `isGet()` | Returns `true` if the request method is GET |
| `isPost()` | Returns `true` if the request method is POST |
| `isPut()` | Returns `true` if the request method is PUT |
| `csrfCheck()` | Validates the CSRF token and redirects if tampering is detected |

<br>

## 6. See Also <a id="see-also"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- [FormHelper Class](forms)
- [CSRF Protection](csrf)
- [Controllers](controllers)