<h1 style="font-size: 50px; text-align: center;">Upgrading to 4.0</h1>

## Table of contents
1. [Overview](#overview)
2. [Update Your User Model](#user_model)
3. [Mailers That Override sendTo()](#mailers)
4. [Password Reset Action](#reset_action)
5. [Profile and Password Forms](#forms)
6. [Password Rules](#password_rules)
7. [Optional: Configure the User Model](#configure)
8. [Optional: Static Analysis](#static_analysis)
9. [Checklist](#checklist)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Version 4.0 moves the definition of a user into the framework.  The columns, validation, and behavior the framework relies on now live in a base class, `Core\Models\User`, and your application's `App\Models\Users` extends it.  Framework code no longer depends on your application's model, so you can rename it or point the framework at a different class.

Most applications need a few small changes, described below.  Code that calls your own model, such as `Users::findById($id)`, keeps working unchanged.

<br>

## 2. Update Your User Model <a id="user_model"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Change `App\Models\Users` to extend `Core\Models\User`, and remove everything the base class now provides:
- The `HasTimestamps`, `IsPrincipal`, `IsUser`, and `PasswordPolicy` traits.
- The standard columns: `id`, `username`, `email`, `fname`, `lname`, `description`, `password`, `confirm`, `acl`, `inactive`, `reset_password`, `login_attempts`, and `deleted`.
- The `$_table`, `$casts`, `whiteList`, `blackList`, and `hidden` declarations, unless you changed them.
- `beforeSave()`, `findByUserName()`, `findAllUsersExceptCurrent()`, `getRememberTokenName()`, and `validator()`, unless you customized them.

Keep anything you added yourself.  A model with no customizations becomes:

```php
namespace App\Models;

use Core\Models\User;

class Users extends User {

}
```

The `Core\Traits\IsUser` trait has been removed.  Its properties and methods are part of `Core\Models\User`.

If you customized `validator()`, keep your override and call `parent::validator()` to keep the framework's rules.  See [The User Model](auth#user_model) for more on customizing.

<br>

## 3. Mailers That Override sendTo() <a id="mailers"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
`AbstractMailer::sendTo()` now takes `Core\Models\User`.  If one of your mailers overrides `sendTo(Users $user)`, PHP stops with a fatal error, because an override may not narrow a parameter type.  Change the parameter type:

```php
use Core\Models\User;

public static function sendTo(User $user): bool {
    // ...
}
```

The same applies to any other framework method you override that took `Users`.  Your `App\Models\Users` objects are still accepted wherever `User` is expected.

<br>

## 4. Password Reset Action <a id="reset_action"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
If your application has its own `AuthController`, created by a kit or included with an earlier starter, change how `resetPasswordAction()` loads the user.  Replace the lookup by id:

```php
$user = Users::findById((int)$id);
$user->password = "";

if(!$user) redirect('');
```

with:

```php
$user = AuthService::pendingPasswordResetUser((int)$id);
if(!$user) {
    flashMessage(Session::INFO, 'Log in to finish resetting your password.');
    redirect('auth.login');
    return;
}
$user->password = "";
```

The reset page is open to guests, so the id in its URL must not decide whose password is changed.  `pendingPasswordResetUser()` only returns the user whose login, in this session, triggered the forced reset.  `AuthService::passwordReset()` performs the same check before saving, but your action should also use it so the reset page does not display another user's details.  See [Forced Password Resets](auth#logging_in).

<br>

## 5. Profile and Password Forms <a id="forms"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Profile editing, the admin user edit page, and the password forms now accept only the fields they need:
- Profile and admin edit forms use the `profileFields` constant: `fname`, `lname`, `email`, and `description`.
- Password reset and password change use the `passwordFields` constant: `password`.

If you added a column that users or administrators edit on these pages, add it to `profileFields` in your model:

```php
class Users extends User {
    public ?string $phone = null;
    public const profileFields = ['fname', 'lname', 'email', 'description', 'phone'];
}
```

If your own controllers assign request data to a user, use these constants with `$user::` instead of `Users::blackList`:

```php
$user->assign($this->request->get(), $user::profileFields, $user::WHITELIST);
```

<br>

## 6. Password Rules <a id="password_rules"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The password rules in `config/password.php` and your `.env` file (`PW_LOWER_CHAR`, `PW_MIN_LENGTH`, and so on) are now enforced when users register, reset, or change their password.  Earlier versions did not apply them correctly, so passwords that were accepted before may now be rejected.  Existing passwords are not affected.

Review the `PW_*` and `SET_PW_*` values in your `.env` file before upgrading.  Set a flag to `false` to stop enforcing that rule.  See [Password Policy](config_env).

Password changes also no longer fail with "This value already exists" for the username.  The username uniqueness check now runs only when a user registers.

<br>

## 7. Optional: Configure the User Model <a id="configure"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
To make the setting visible, or to use a class other than `App\Models\Users`, add `config/auth.php`:

```php
<?php
/**
 * Authentication Configuration.
 */

return [
    // The model the framework uses for users.  Must extend Core\Models\User.
    'user_model' => App\Models\Users::class,
];
```

Without this file the framework uses `App\Models\Users`.

<br>

## 8. Optional: Static Analysis <a id="static_analysis"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
New applications include PHPStan.  To add it to an existing application:

1. Install PHPStan as a development dependency:
```sh
composer require --dev phpstan/phpstan
```

2. Add an `analyse` script to `composer.json`:
```json
"scripts": {
    "analyse": "phpstan analyse --memory-limit=1G"
}
```

3. Create `phpstan.neon.dist` in the project root:
```yaml
parameters:
    level: 5

    paths:
        - app
        - database

    bootstrapFiles:
        - vendor/chappy-php/chappy-php-framework/src/scripts/analysis_bootstrap.php

    tmpDir: storage/cache/phpstan
```

4. Add `/phpstan.neon` to `.gitignore` so personal overrides are not committed.

5. Run `composer analyse`.  If the first run reports many errors, generate a baseline with `composer analyse -- --generate-baseline` and include it in your configuration.

See [Static Analysis (PHPStan)](static_analysis) for details.

<br>

## 9. Checklist <a id="checklist"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- `App\Models\Users` extends `Core\Models\User`, with duplicated members removed.
- No remaining references to `Core\Traits\IsUser`.
- Mailer `sendTo()` overrides take `User`.
- `resetPasswordAction()` uses `AuthService::pendingPasswordResetUser()`.
- Custom editable columns added to `profileFields`.
- `PW_*` and `SET_PW_*` values in `.env` reviewed.
- Registration, login, logout, a forced password reset, a password change, and a profile edit tested.
- Optional: `composer analyse` runs cleanly or with a committed baseline.
