<h1 style="font-size: 50px; text-align: center;">User Model</h1>

## Table of contents
1. [Overview](#overview)
2. [Your Application's User Model](#app_model)
3. [Columns](#columns)
4. [Field Lists](#field_lists)
5. [Saving Users](#saving)
6. [Validation](#validation)
7. [Finding Users](#finding)
8. [Access Control Levels](#acls)
9. [Account Status](#account_status)
10. [Authentication](#authentication)
11. [Serialization](#serialization)
12. [Customizing the User Model](#customizing)
13. [Using a Different User Model](#different_model)
14. [Related Services](#related_services)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
User accounts are represented by `Core\Models\User`, a model provided by the framework.  It defines the columns of the `users` table and everything the framework needs from a user: password hashing, validation, access control levels (ACLs), account status flags, soft deletes, and authentication.

Your application does not use `Core\Models\User` directly.  It has its own user model, `App\Models\Users`, which extends it.  Everything described on this page is available on your model, and anything you add to your model is available wherever the framework works with users.

`Core\Models\User` extends `Core\Model`, so all of the standard model features described in [Models](models) also apply.

<br>

## 2. Your Application's User Model <a id="app_model"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
New applications include `app/Models/Users.php`:

```php
namespace App\Models;

use Core\Models\User;

class Users extends User {

}
```

The class is empty because everything it needs comes from `Core\Models\User`.  Add your own columns, methods, and relationships here.  See [Customizing the User Model](#customizing).

In your application code, always use your own model:

```php
use App\Models\Users;

$user = Users::findById($id);
```

<br>

## 3. Columns <a id="columns"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `users` table is created by the users migration included with new applications.  Each column is a public property on the model.

| Column | Type | Description |
|:---|:---|:---|
| `id` | `int` | Primary key. |
| `username` | `string` | Login name.  Must be unique, between 6 and 150 characters. |
| `email` | `string` | Email address, up to 150 characters. |
| `password` | `string` | Password hash.  Hashed automatically when saved.  See [Saving Users](#saving). |
| `fname` | `string` | First name, up to 150 characters. |
| `lname` | `string` | Last name, up to 150 characters. |
| `description` | `string` or `null` | Optional profile description. |
| `acl` | `string` | The user's access control levels, stored as a JSON array.  See [Access Control Levels](#acls). |
| `inactive` | `int` | `1` if the account is deactivated and cannot log in. |
| `reset_password` | `int` | `1` if the user must set a new password at their next login. |
| `login_attempts` | `int` | Failed login attempts since the last successful login. |
| `deleted` | `int` | `1` if the user has been soft deleted. |
| `created_at`, `updated_at` | `string` | Set automatically when the user is saved. |

The model also has a `confirm` property for the password confirmation field on forms.  It is not a database column.

<br>

## 4. Field Lists <a id="field_lists"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The model defines constants that control which fields can be set from a form and which are shown when a user is serialized.

| Constant | Default | Used for |
|:---|:---|:---|
| `whiteList` | `username`, `email`, `password`, `fname`, `lname`, `description` | Registration. |
| `profileFields` | `fname`, `lname`, `email`, `description` | Profile editing and the admin user edit page. |
| `passwordFields` | `password` | Password reset and password change. |
| `blackList` | `id`, `deleted` | Fields that may never be assigned. |
| `hidden` | `password`, `deleted`, `confirm`, `login_attempts` | Left out when the user is serialized to JSON.  See [Serialization](#serialization). |

Pass a list to `assign()` together with the mode it uses:

```php
$user->assign($this->request->get(), $user::profileFields, $user::WHITELIST);
```

Use `$user::` rather than the class name, so a list you have extended in your own model is used.  Username, password, ACLs, and status flags each have their own flow and are never accepted from the profile forms.  See [Mass Assignment](models#best-practice).

<br>

## 5. Saving Users <a id="saving"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Before a user is saved, the model:
- Sets `created_at` and `updated_at`.
- Hashes the password when the user is new.
- Hashes the password and clears `reset_password` when a password change is in progress.
- Stores an empty ACL list as `[""]`.

**Changing a Password**  
A password is only hashed when the user is new or when a password change has been switched on with `setChangePassword()`.  Without it, a new value in `password` would be saved as plain text.  Set the new password and its confirmation, then save:

```php
$user->password = $newPassword;
$user->confirm = $newPassword;
$user->setChangePassword(true);

if($user->save()) {
    $user->setChangePassword(false);
}
```

`UserService::updatePassword()` and `AuthService::passwordReset()` do this for the profile and password reset pages.

**Creating Users in Code**  
Set `acl` before saving a new user.  Registration uses `ACLService::setAclAtRegistration()`, which gives the first user the `Admin` level and every other user none:

```php
$user = new Users();
$user->assign($data, Users::whiteList, Users::WHITELIST);
$user->confirm = $data['password'];
$user->acl = ACLService::setAclAtRegistration();
$user->save();
```

For test data, use the built-in `UserFactory`.  See [Factories](factories).

**Soft Deletes**  
Users are soft deleted.  Calling `delete()` sets `deleted` to `1` instead of removing the row, and deleted users are left out of `find()`, `findFirst()`, and the other finders.

<br>

## 6. Validation <a id="validation"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
`validator()` runs automatically when a user is saved.  It checks:
- `fname`, `lname`, and `email` are present and at most 150 characters.
- `username` and `password` are present.
- For new users, `username` is between 6 and 150 characters and not already taken.
- For new users and password changes, the password meets your password policy and matches `confirm`.

The password policy rules (minimum and maximum length, and required lowercase, uppercase, numeric, and special characters) are read from `config/password.php`.  Each rule can be switched off.  See [Password Policy](config_env).

When validation fails, `save()` returns `false` and the messages are available from `getErrorMessages()`.  To change the rules, see [Customizing the User Model](#customizing).

<br>

## 7. Finding Users <a id="finding"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
In addition to the standard finders such as `findById()`, `findFirst()`, `find()`, and `findTotal()`, the user model provides:

**findByUserName()**  
Finds a user by username.

```php
$user = Users::findByUserName('chad');
```

**findAllUsersExceptCurrent()**  
Returns every user except the one with the given id.  The admin dashboard uses it to list other users.  Additional query conditions can be passed as the second argument.

```php
$users = Users::findAllUsersExceptCurrent(AuthService::currentUser()->id);

$users = Users::findAllUsersExceptCurrent($currentId, [
    'order' => 'lname, fname'
]);
```

Finders return `false` when no user is found.  Check the result before using it:

```php
$user = Users::findById($id);
if(!$user) {
    redirect('');
    return;
}
```

To get the currently logged in user, use `AuthService::currentUser()` or `Auth::user()`.  See [Authentication](auth#current_user).

<br>

## 8. Access Control Levels <a id="acls"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
A user's access control levels are stored in `acl` as a JSON array, for example `["Admin","Editor"]`.  A user with no levels has `[""]`.

**hasAcl()**  
Returns `true` if the user has the given level.

```php
if($user->hasAcl('Admin')) {
    // ...
}
```

**getAcls()**  
Returns the user's levels as an array.

```php
foreach($user->getAcls() as $acl) {
    echo $acl;
}
```

To grant or remove levels, use `ACLService`.  See [ACL Service](acl_service) and [Access Control Levels](access_control_levels).

<br>

## 9. Account Status <a id="account_status"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Two flags control what a user can do at login:
- `inactive` - The account is deactivated.  Login is refused.  Accounts are deactivated automatically after too many failed login attempts.  See [Authentication](auth#logging_in).
- `reset_password` - The user must set a new password at their next login.  See [Forced Password Resets](auth#logging_in).

Both have helpers for checkboxes on admin forms:

```php
$user->isInactiveChecked();   // true when inactive is 1
$user->isResetPWChecked();    // true when reset_password is 1
```

To change these flags from the admin dashboard, use `UserService::toggleAccountStatus()` and `UserService::toggleResetPassword()`.  See [User Service](user_service).

<br>

## 10. Authentication <a id="authentication"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The user model implements the `Principal` contract, which is what the authentication system works with.  It identifies users by `id` and verifies passwords against `password`:

```php
$user->getAuthIdentifier();   // the user's id
$user->getAuthPassword();     // the password hash
```

Remember me tokens are stored per device in the `user_sessions` table, not on the user, so `getRememberTokenName()` returns `null`.

See [Authentication](auth) for logging in, logging out, remember me, and the guard.

<br>

## 11. Serialization <a id="serialization"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
When a user is serialized to JSON, the fields in `hidden` are left out: `password`, `deleted`, `confirm`, and `login_attempts`.  This applies to `json_encode()`, API responses, and the props passed to React pages, so the password hash never reaches the browser.

```php
$json = json_encode($user);   // no password, deleted, confirm, or login_attempts
```

`acl`, `inactive`, and `reset_password` are included.  To change what is exposed, see [Hiding Fields](#customizing) below and [Hiding Fields From Output](models#hidden-fields).

`toArray()` is different: it returns every public property, including the password hash, with `created_at` and `updated_at` converted using the model's casts.  Use it inside your application, never for output.

The `hidden` list is a safety net, not a substitute for choosing what a page receives.  When passing users to React pages, prefer an array containing only the fields the page needs, such as the one `DashboardService::userToArray()` builds.  See [Pagination](pagination).

<br>

## 12. Customizing the User Model <a id="customizing"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
**Adding Columns**  
Add a migration for the column, then declare it as a public property.  To let users edit it on their profile, add it to `profileFields`:

```php
namespace App\Models;

use Core\Models\User;

class Users extends User {
    public ?string $phone = null;

    public const profileFields = ['fname', 'lname', 'email', 'description', 'phone'];
}
```

**Hiding Fields**  
Override `hidden` to leave more fields out of JSON output.  Include the defaults:

```php
public const hidden = ['password', 'deleted', 'confirm', 'login_attempts', 'phone'];
```

Or declare a `visible` list to expose only the fields you name.  This is safer, because a column added later stays out of the output until you add it to the list:

```php
public const visible = ['id', 'username', 'fname', 'lname', 'description'];
```

**Changing Validation**  
Override `validator()`.  Call `parent::validator()` to keep the framework's rules and add your own:

```php
public function validator(): void {
    parent::validator();
    $this->runValidation('phone', ['max:20'], 'Phone');
}
```

**Adding Methods and Relationships**  
Add methods as you would on any model.  The framework returns instances of your model, so your methods are available on users from `AuthService::currentUser()`, the finders, and `UserFactory`.

```php
public function fullName(): string {
    return "{$this->fname} {$this->lname}";
}
```

<br>

## 13. Using a Different User Model <a id="different_model"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The framework uses `App\Models\Users` by default.  To use a different class, set `user_model` in `config/auth.php`.  The class must extend `Core\Models\User`.

```php
return [
    'user_model' => App\Models\Members::class,
];
```

The framework finds the configured class with `Auth::userModel()`, so authentication, lookups, and `UserFactory` all return instances of it.  The auth, profile, and admin dashboard kits generate code that uses the configured class name.  If `user_model` names a class that does not exist or does not extend `Core\Models\User`, the framework throws an exception explaining what to fix.

<br>

## 14. Related Services <a id="related_services"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- [Auth Service](auth_service) - Login, logout, the current user, profile images, and password resets.
- [User Service](user_service) - Password changes, account status, and the admin dashboard.
- [ACL Service](acl_service) - Granting and removing access control levels.
- [Dashboard Service](dashboard_service) - User listings for the admin dashboard.
- [Factories](factories) - Creating users for tests and seeders with `UserFactory`.
