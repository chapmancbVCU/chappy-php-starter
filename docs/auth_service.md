<h1 style="font-size: 50px; text-align: center;">AuthService</h1>

## Table of contents
1. [Overview](#overview)
2. [Public Methods](#public-methods)
3. [Related Components](#related-components)
4. [Notes](#notes)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `AuthService` class manages all user authentication processes, including login, logout, session handling, password resets, and "remember me" cookie-based login. It integrates directly with your session, cookie, and logging subsystems.

<br>

**Setup**
```php
use Core\Services\AuthService;
```

<br>

**Common Use Cases**
- Log users in or out
- Manage login attempts and account locking
- Handle "remember me" sessions
- Reset user passwords
- Upload profile images

<br>

## 2. Public Methods <a id="public-methods"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `confirm()`
Returns the value of the `confirm` field from the request, typically used for password confirmation.

Parameter:
- `Input $request` - The request.

Returns:
- `string` - The value of the confirm field.
```php
$confirm = AuthService::confirm($request);
```

<br>

### B. `currentUser()`
Retrieves the currently logged-in user from the session, or loads it from the database if not cached.

Returns:
- `User|false|null` An object containing information about current logged in user from users table.

```php
$user = AuthService::currentUser();
```

<br>

### C. `hashPassword()`
Hashes a plain text password using PHP's `password_hash()` with the default algorithm.

Parameter:
- `string $password` - Original password submitted on a registration or update password form.

Returns:
- `string` - The hashed version of the password.

```php
$hash = AuthService::hashPassword($rawPassword);
```

<br>

### D. `login()`
Attempts to log a user in. If successful, resets login attempts and creates a session. Otherwise, tracks failed attempts and optionally triggers an email notification.  If the account is flagged for a password reset, records the pending reset with `beginPasswordReset()` and redirects to the reset page.  New code should use `LoginService::attempt()`, described in [Authentication](auth#logging_in).

Parameters:
- `Input $request` - The request for the login.
- `Login $loginModel` - The login model.
- `string $username` - The user to be logged in.
- `bool $mailer` - Sends account deactivated E-mail when user surpasses max number of login attempts before account is locked.

Returns:
- `Login` - Model that handles logins.

```php
$loginModel = AuthService::login($request, new Login(), 'johndoe', true);
```

<br>

### E. `loginAttempts()`
Increments login attempt counter, locks the account if maximum attempts are reached, and optionally sends an account deactivation email.

- `User $user` - The user whose login attempts we are tracking.
- `Login $loginModel` - The model that will be responsible for displaying messages.
- `bool $mailer` - Sends account deactivated E-mail when user surpasses max number of login attempts before account is locked.

Returns:
- `Login $loginModel` - The Login model after login in attempt test and session messages are assigned.

```php
AuthService::loginAttempts($user, $loginModel, true);
```

<br>

### F. `loginUser()`
Creates a session for the logged-in user and stores a remember-me token if requested.

Parameters:
- `User $loginUser` - The user to be logged in.
- `bool $rememberMe` - Value obtained from remember me checkbox found in login form.  Default value is false.

```php
AuthService::loginUser($user, true);
```

<br>

### G. `loginUserFromCookie`
Attempts to log in a user from a remember me cookie. If valid, creates a session and returns the user.

Returns:
- `Users` - The user associated with previous session.

```php
$user = AuthService::loginUserFromCookie();
```

<br>

### H. `logout()`
Logs out the currently logged-in user by clearing the session and deleting any active cookies.

```php
AuthService::logout();
```

<br>

### I. `logoutUser()`
Clears the user's session and remember-me cookie. Also removes the corresponding record from the `user_sessions` table.

Parameter:
- `User $user` - The user to be logged out.

Returns:
- `bool` - Returns true if operation is successful.

```php
AuthService::logoutUser($user);
```

<br>

### J. `passwordReset()`
Handles the complete flow of resetting a user’s password, including setting the confirmation field and updating the record.

The reset only proceeds if this session has a pending reset for `$user` (see `pendingPasswordResetUser()` below).  Otherwise it logs a warning and redirects to the login page without saving.  Only the fields in the model's `passwordFields` constant are taken from the request.  After a successful save, the pending reset is cleared and the user is redirected to the login page.

Parameters:
- `Input $request` - The request for the password reset action.
- `User $user` - The user whose password we will reset.

```php
AuthService::passwordReset($request, $user);
```

<br>

### K. `beginPasswordReset()`
Records in the session that a user has proven their credentials and must set a new password.  Login calls this when it finds the `reset_password` flag, before redirecting to the reset page.

Parameters:
- `User $user` - The user who must reset their password.

```php
AuthService::beginPasswordReset($user);
```

<br>

### L. `pendingPasswordResetUser()`
Returns the user whose password may be reset in this session, or `null`.  The id must match the user recorded by `beginPasswordReset()`, and that user must still have the `reset_password` flag set.  Use this in your reset action instead of looking the user up by the id in the URL.

Parameters:
- `int $id` - The user id from the reset page URL.

Returns:
- `User|null` - The user, or `null` if this session has no pending reset for that id.

```php
$user = AuthService::pendingPasswordResetUser((int)$id);
if(!$user) {
    redirect('auth.login');
    return;
}
```

<br>

### M. `profileImageUpload()`
Processes a profile image upload and returns an `Uploads` object. Assumes the input name is `profileImage`.

Parameter:
- `User $user` - The user who uploaded a profile image.

Returns:
- `Uploads|null` - The uploads object if it's created or null.

```php
$upload = AuthService::profileImageUpload($user);
```

<br>

## 3. Related Components<a id="related-components"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- `User` – Base user model; your configured user model (`App\Models\Users` by default) extends it.
- `Login` – Model used to store validation and error states during login.
- `UserSessions` – Tracks persistent sessions for "remember me" functionality.
- ``Uploads`` – Used for uploading profile images.
- `AccountDeactivatedMailer` – Sends account lockout notifications when enabled.

<br>

## 4. Notes <a id="notes"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- Session and cookie names are retrieved from environment variables via `Env::get(...)`.
- Login attempt limits and remember-me expiration are also configurable via `.env`:
    - `MAX_LOGIN_ATTEMPTS`
    - `REMEMBER_ME_COOKIE_NAME`
    - `REMEMBER_ME_COOKIE_EXPIRY`
- The `loginUser()` method logs to the app's logging system using `Logger`.