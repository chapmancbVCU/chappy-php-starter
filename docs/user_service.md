<h1 style="font-size: 50px; text-align: center;">UserService</h1>

## Table of contents
1. [Overview](#overview)
2. [Public Methods](#public-methods)
3. [Related Components](#related-components)
4. [Notes](#notes)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `UserService` class provides high-level user management operations such as account deactivation, password updates, profile image handling, and access restrictions. It is designed to support both user self-management and admin-level user administration.

**Setup**
```php
use Core\Services\UserService;
```

✅ **Common Use Cases**
- Safely delete users (excluding admins)
- Manage and sort profile images
- Update and validate user passwords
- Handle account deactivation and reset flags
- Send user-related emails (e.g., password reset, deactivation)

<br>

## 2. ⚙️ Public Methods <a id="public-methods"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
`deleteIfAllowed(int $id, bool $unlink = false): void`

Deletes a user if they are not an admin. Optionally removes their profile images if `$unlink` is `true`.
```php
UserService::deleteIfAllowed(5, true);
```

<br>

`deleteProfileImage(Input $request): array`

Deletes a profile image based on an ID passed via request. Returns a JSON-compatible response array.
```php
$response = UserService::deleteProfileImage($request);
```

<br>

`ensureAuthenticatedUser(Users $user): void`

Ensures that the user being modified matches the currently logged-in user. If not, redirects with an error message.
```php
UserService::ensureAuthenticatedUser($user);
```

<br>

`handleProfileImages(Users $user, ?Uploads $uploads, ?string $sortedImages): void`

Handles profile image uploading and image order sorting.
```php
UserService::handleProfileImages($user, $uploads, $sortedJson);
```

<br>

`updatePassword(Users $user, Input $request): bool`

Updates the user’s password if the current password is correct and the new password passes validation.
```php
$success = UserService::updatePassword($user, $request);
```

<br>

`sendWhenSetToInactive(Users $user, bool $shouldSendEmail = false): void`

Sends an account deactivation email if `$shouldSendEmail` is `true`.
```php
UserService::sendWhenSetToInactive($user, true);
```

<br>

`sendWhenSetToResetPW(Users $user, bool $shouldSendEmail = false): void`

Sends a password reset email if `$shouldSendEmail` is `true`.
```php
UserService::sendWhenSetToResetPW($user, true);
```

<br>

`toggleAccountStatus(Users $user, Input $request, ?int $currentInactive = null): bool`

Toggles the `inactive` status based on request input. Returns `true` if the account was just deactivated.
```php
$shouldEmail = UserService::toggleAccountStatus($user, $request, $previousInactive);
```

<br>

`toggleResetPassword(Users $user, Input $request, ?int $currentReset = null): bool`

Toggles the `reset_password` flag based on request input. Returns `true` if it was just activated.
```php
$shouldEmail = UserService::toggleResetPassword($user, $request, $previousReset);
```

<br>

## 3. 📦 Related Components<a id="related-components"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- `AuthService` – Used to validate current user identity and confirm password fields.
- `ProfileImages` – Handles image persistence, deletion, and sorting.
- `Uploads` – File upload handler.
- `AccountDeactivatedMailer / PasswordResetMailer` – Responsible for user notification emails.
- `Users` – Model representing application users.

<br>

## 4. 🧠 Notes <a id="notes"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- Admin users (`["Admin"]` ACL) are protected from deletion.
- Upload handling assumes that `$_FILES['profileImage']` is present for Uploads.
- Email methods like `sendWhenSetToInactive()` and `sendWhenSetToResetPW()` rely on `AccountDeactivatedMailer` and `PasswordResetMailer` respectively.
- `toggleAccountStatus()` and `toggleResetPassword()` help controllers determine if emails should be triggered post-form submission.
