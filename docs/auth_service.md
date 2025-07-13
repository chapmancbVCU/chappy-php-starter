<h1 style="font-size: 50px; text-align: center;">AuthService</h1>

## Table of contents
1. [Overview](#overview)
2. [Public Methods](#public-methods)
3. [Related Components](#related-components)
4. [Notes](#notes)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `AuthService` class manages all user authentication processes, including login, logout, session handling, password resets, and "remember me" cookie-based login. It integrates directly with your session, cookie, and logging subsystems.

**Setup**
```php
use Core\Services\AuthService;
```

✅ **Common Use Cases**
- Log users in or out
- Manage login attempts and account locking
- Handle "remember me" sessions
- Reset user passwords
- Upload profile images

<br>

## 2. ⚙️ Public Methods <a id="public-methods"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
`confirm(Input $request): string`

Returns the value of the `confirm` field from the request, typically used for password confirmation.

```php
$confirm = AuthService::confirm($request);
```

<br>

`currentUser(): ?Users`

Retrieves the currently logged-in user from the session, or loads it from the database if not cached.
```php
$user = AuthService::currentUser();
```

<br>

`hashPassword(string $password): string`

Hashes a plain text password using PHP's `password_hash()` with the default algorithm.
```php
$hash = AuthService::hashPassword($rawPassword);
```

<br>

`login(Input $request, Login $loginModel, string $username, bool $mailer = false): Login`

Attempts to log a user in. If successful, resets login attempts and creates a session. Otherwise, tracks failed attempts and optionally triggers an email notification.
```php
$loginModel = AuthService::login($request, new Login(), 'johndoe', true);
```

<br>

`loginAttempts(Users $user, Login $loginModel, bool $mailer = false): Login`

Increments login attempt counter, locks the account if maximum attempts are reached, and optionally sends an account deactivation email.
```php
AuthService::loginAttempts($user, $loginModel, true);
```

<br>

`loginUser(Users $loginUser, bool $rememberMe = false): void`

Creates a session for the logged-in user and stores a remember-me token if requested.
```php
AuthService::loginUser($user, true);
```

<br>

`loginUserFromCookie(): ?Users`

Attempts to log in a user from a remember me cookie. If valid, creates a session and returns the user.
```php
$user = AuthService::loginUserFromCookie();
```

<br>

`logout(): void`

Logs out the currently logged-in user by clearing the session and deleting any active cookies.
```php
AuthService::logout();
```

<br>

`logoutUser(Users $user): bool`

Clears the user's session and remember-me cookie. Also removes the corresponding record from the `user_sessions` table.
```php
AuthService::logoutUser($user);
```

<br>

`passwordReset(Input $request, Users $user): void`

Handles the complete flow of resetting a user’s password, including setting the confirmation field and updating the record.
```php
AuthService::passwordReset($request, $user);
```

<br>

`profileImageUpload(Users $user): ?Uploads`

Processes a profile image upload and returns an `Uploads` object. Assumes the input name is `profileImage`.
```php
$upload = AuthService::profileImageUpload($user);
```

<br>

## 3. 📦 Related Components<a id="related-components"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- `Users` – User model used for authentication and lookup.
- `Login` – Model used to store validation and error states during login.
- `UserSessions` – Tracks persistent sessions for "remember me" functionality.
- ``Uploads`` – Used for uploading profile images.
- `AccountDeactivatedMailer` – Sends account lockout notifications when enabled.

<br>

## 4. 🧠 Notes <a id="notes"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- Session and cookie names are retrieved from environment variables via `Env::get(...)`.
- Login attempt limits and remember-me expiration are also configurable via `.env`:
    - `MAX_LOGIN_ATTEMPTS`
    - `REMEMBER_ME_COOKIE_NAME`
    - `REMEMBER_ME_COOKIE_EXPIRY`
- The `loginUser()` method logs to the app's logging system using `Logger`.
- The service makes use of a `$currentLoggedInUser` static cache to prevent redundant database queries.