<h1 style="font-size: 50px; text-align: center;">Authentication</h1>

## Table of contents
1. [Overview](#overview)
2. [Checking the Current User](#current_user)
3. [Logging In](#logging_in)
4. [Logging Out](#logging_out)
5. [Remember Me](#remember_me)
6. [The User Model](#user_model)
7. [Making a Model Authenticatable](#model_auth)
8. [Password Hashing](#password_hashing)
9. [Architecture (Advanced)](#architecture)
10. [Environment Configuration](#environment_configuration)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Chappy.php provides a session-based authentication system for logging users in and out, checking who is currently authenticated, and optionally keeping users logged in across browser sessions via a "remember me" token. The system is built around a small set of pieces that work together: a static `Auth` entry point for everyday use, a `SessionGuard` that owns the current authenticated user for a request, a `UserProvider` that retrieves users from storage, and a `Hasher` for password verification. Most applications interact only with the `Auth` entry point and the login flow; the underlying pieces are pluggable for applications with custom authentication needs.

By default, authentication is backed by your application's `App\Models\Users` model and verifies passwords with bcrypt.  The model is configurable; see [The User Model](#user_model). Remember-me tokens are stored as hashes in the `user_sessions` table, keyed per device, so a leaked session table never exposes usable tokens.

<br>

## 2. Checking the Current User <a id="current_user"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `Core\Lib\Auth\Auth` class is the entry point for reading authentication state anywhere in your application. It resolves the current user once per request and caches the result.

```php
use Core\Lib\Auth\Auth;

// Is anyone logged in?
if (Auth::check()) {
    // ...
}

// Get the current user (a Principal, or null if not logged in)
$user = Auth::user();

// Get just the current user's id without loading the full object
$id = Auth::id();
```

`Auth::user()` returns the authenticated model (an instance of your configured user model, `App\Models\Users` by default) or `null` when no one is logged in. Because the result is cached for the request, calling it repeatedly does not repeatedly hit the database.

<br>

## 3. Logging In <a id="logging_in"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Login is handled by `Core\Services\LoginService::attempt()`, which verifies credentials and applies your account rules (password-reset flag, inactive accounts, failed-attempt lockout) and returns a `LoginResult` describing the outcome. The controller then decides how to respond. This separation keeps the login decision testable and independent of redirects and flash messages.

```php
use Core\Services\LoginService;
use Core\Lib\Auth\LoginResult;

$result = LoginService::attempt($this->request, $loginModel, $username);

switch ($result->status) {
    case LoginResult::SUCCESS:
        redirect(env('DEFAULT_CONTROLLER'));
        break;
    case LoginResult::NEEDS_RESET:
        redirect('auth.resetPassword', [$result->user->id]);
        break;
    case LoginResult::INACTIVE:
        flashMessage(Session::DANGER, 'Account is currently inactive');
        redirect('auth.login');
        break;
    case LoginResult::LOCKED:
        flashMessage(Session::DANGER, 'Your account has been locked due to too many failed login attempts.');
        redirect('auth.login');
        break;
    case LoginResult::INVALID:
        // Error message already added to the login model; fall through to re-render.
        break;
}
```

<br>

**Login Outcomes**
The `LoginResult` status is one of:
- `SUCCESS` - credentials valid; the user is logged in.
- `NEEDS_RESET` - credentials valid, but the account is flagged for password reset.
- `INACTIVE` - credentials valid, but the account is marked inactive.
- `LOCKED` - credentials invalid, and the account has just been locked after too many failed attempts.
- `INVALID` - credentials invalid (wrong password or unknown user).

When the result carries a relevant user (`SUCCESS`, `NEEDS_RESET`), it is available as `$result->user`.

<br>

**Failed Attempts and Lockout**
Each failed login against a known account increments that account's `login_attempts`. Once attempts reach `MAX_LOGIN_ATTEMPTS` (configurable via environment, default 5), the account is marked inactive and the result becomes `LOCKED`. A successful login resets the counter to zero. Optionally, an account-deactivated email can be sent on the transition into the locked state by passing the mailer flag to `attempt()`.

<br>

**Forced Password Resets**
When an administrator flags an account for a password reset, the user's next login returns `NEEDS_RESET` and the controller redirects to the reset page.  The reset page only works for that user, in that session:
- `LoginService::attempt()` records the user's id in the session with `AuthService::beginPasswordReset()` after the current password has been verified.
- The reset page loads the user with `AuthService::pendingPasswordResetUser($id)`, which returns `null` unless the id matches the one recorded at login and the account is still flagged.
- `AuthService::passwordReset()` refuses to save unless the same check passes, and clears the session entry after a successful reset.

The user id in the reset URL is therefore not enough to reset a password.  A visitor who opens the reset link from an email without logging in first is sent to the login page, and logging in brings them back to the reset page.

```php
public function resetPasswordAction(int $id): void {
    $user = AuthService::pendingPasswordResetUser((int)$id);
    if(!$user) {
        flashMessage(Session::INFO, 'Log in to finish resetting your password.');
        redirect('auth.login');
        return;
    }
    // ...
}
```

<br>

## 4. Logging Out <a id="logging_out"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
```php
use Core\Lib\Auth\Auth;

Auth::guard()->logout();
```
Logging out clears the session, the cached user, and any persisted remember-me token and cookie.

<br>

## 5. Remember Me <a id="remember_me"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
When the login form's "remember me" option is selected, a persistent token is issued so the user is automatically logged back in on a future visit after their session has ended.

How it works:
- A cryptographically random token is generated and stored in the user's browser as a cookie.
- Only a hash of that token is stored in the `user_sessions` table, alongside the user id and a user-agent fingerprint.
- On a later visit with no active session, the incoming cookie token is hashed and matched against the stored hash to re-authenticate the user.

Because tokens are keyed per device (user id plus user agent), each device holds an independent token, and logging in on one device does not invalidate others. Because only hashes are stored, a compromised `user_sessions` table does not expose working tokens.

Relevant environment values:
- `REMEMBER_ME_COOKIE_NAME` — the cookie name.
- `REMEMBER_ME_COOKIE_EXPIRY` — cookie lifetime in seconds (default 2592000, i.e. 30 days).

<br>

## 6. The User Model <a id="user_model"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Authentication works with your application's user model, `App\Models\Users`, which extends the framework's `Core\Models\User`.  The base class supplies the columns, password hashing, validation, ACLs, and account status flags, and implements the `Principal` contract that authentication relies on.

To use a different class, set `user_model` in `config/auth.php`.  Framework code finds the configured class with `Auth::userModel()`.

See [User Model](user_model) for everything the model provides and how to customize it.

<br>

## 7. Making a Model Authenticatable <a id="model_auth"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Your user model is already authenticatable, because `Core\Models\User` implements the `Principal` contract.  This section applies to other models, such as one representing an API client, that need to participate in authentication.

Implement the `Principal` contract on the model.  The `IsPrincipal` trait provides a default implementation that reads values from the model's own properties.

```php
use Core\Model;
use Core\Lib\Contracts\Principal;
use Core\Traits\IsPrincipal;

class ApiClients extends Model implements Principal {
    use IsPrincipal;
    // ...
}
```

By default, the trait expects an `id` identifier column and a `password` column. To point a model at differently-named columns, override the corresponding getter method (for example, `getAuthPasswordName()`). Returning `null` from `getRememberTokenName()` disables the token-column feature for a model — appropriate when remember-me state lives outside the table, as it does by default (in `user_sessions`).

<br>

## 8. Password Hashing <a id="password_hashing"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Passwords are hashed with bcrypt through the `Hasher` contract (`BcryptHasher` by default). Hashing typically happens automatically in the model's save lifecycle, so application code rarely calls the hasher directly. When verifying credentials, the provider checks the supplied password against the stored hash; existing hashes remain valid, and the system can flag hashes for upgrade when the configured cost changes.

<br>

## 9. Architecture (Advanced) <a id="architecture"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Most applications do not need this section. It describes the pieces behind `Auth` for those implementing custom authentication backends.

- `Auth` — a static entry point that lazily constructs and shares a single guard per request, and forwards `check()`, `user()`, and `id()` to it. A guard can be injected via `Auth::setGuard()`, primarily for testing.
- `SessionGuard` — owns "who is logged in right now" for a request. It reads and writes the session, caches the resolved user, and delegates user retrieval to a provider and remember-me persistence to a store.
- `UserProvider` — retrieves and validates users from storage. The default `ModelUserProvider` is backed by the configured user model (see [The User Model](#user_model)). Retrieval and validation are kept separate: a user can be located by credentials without verifying them, and verified without being re-fetched. This is what allows failed-attempt tracking to distinguish "unknown user" from "wrong password."
- `Hasher` — hashes and verifies passwords behind a single contract, so the algorithm can be changed in one place.

To authenticate against a different backend (for example, an external directory), implement `UserProvider` and construct the guard with it. The guard, session handling, and `Auth` entry point are reused unchanged.

<br>

## 10. Environment Configuration <a id="environment_configuration"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

| Command | Description | Arguments |
|---------|-------------|-----------|
| CURRENT_USER_SESSION_NAME | Session key holding the authenticated user's id | - |
| MAX_LOGIN_ATTEMPTS | Failed attempts before an account locks | 5 |
| REMEMBER_ME_COOKIE_NAME | Remember-me cookie name | - |
| REMEMBER_ME_COOKIE_EXPIRY | Remember-me cookie lifetime (seconds) | 2592000 |
| DEFAULT_CONTROLLER | Where to send a user after successful login	| — |