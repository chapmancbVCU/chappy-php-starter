<h1 style="font-size: 50px; text-align: center;">UserService</h1>

## Table of contents
1. [Overview](#overview)
2. [Public Methods](#public-methods)
3. [Related Components](#related-components)
4. [Examples](#examples)
5. [Notes](#notes)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `UserService` class provides high-level user management operations such as account deactivation, password updates, profile image handling, and access restrictions. It is designed to support both user self-management and admin-level user administration.

<br>

**Setup**
```php
use Core\Services\UserService;
```

<br>

**Common Use Cases**
- Safely delete users (excluding admins)
- Manage and sort profile images
- Update and validate user passwords
- Handle account deactivation and reset flags
- Send user-related emails (e.g., password reset, deactivation)

<br>

## 2. Public Methods <a id="public-methods"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `deleteIfAllowed()`
Deletes a user if they are not an admin. Optionally removes their profile images if `$unlink` is `true`.  Appropriate messaging is displayed based on success or failure.

Parameters:
- `int $id` - The id for user we want to delete.
- `bool $unlink` - Determines if profile images are deleted.

```php
UserService::deleteIfAllowed(5, true);
```

<br>

### B. `deleteProfileImage()`
Deletes a profile image based on an ID passed via request. Returns a JSON-compatible response array.

Parameter:
- `Input $request` - The request for deleting image.

Returns:
- `array` - JSON response array.

```php
$response = UserService::deleteProfileImage($request);
```

<br>

### C. `ensureAuthenticatedUser()`
Ensures that the user being modified matches the currently logged-in user. If not, redirects with an error message.

Parameter:
- `Users $user` - The user object to test.

```php
UserService::ensureAuthenticatedUser($user);
```

<br>

### D. `handleProfileImages()`
Handles profile image uploading and image order sorting.

Parameters:
- `Users $user` - The user whose profile images we want to manage.
- `Uploads|null` - $uploads The Uploads object or profile image upload.
- `string|null` - $sortedImages Order of sorted images.

```php
UserService::handleProfileImages($user, $uploads, $sortedJson);
```

<br>

### E. `updatePassword()`
Updates the user’s password if the current password is correct and the new password passes validation.

Parameters:
- `Users $user` - The user whose password we want to update.
- `Input $request` - The request.

Returns:
- `bool` - True if password is updated, otherwise false.

```php
$success = UserService::updatePassword($user, $request);
```

<br>

### F. `sendWhenSetToInactive()`
Sends an account deactivation email if `$shouldSendEmail` is `true`.

Parameters:
- Users $user The user we will send E-mail to.
- bool $shouldSendEmail Sends E-mail when true.

```php
UserService::sendWhenSetToInactive($user, true);
```

<br>

### G. `sendWhenSetToResetPW()`
Sends a password reset email if `$shouldSendEmail` is `true`.

Parameters:
- `Users $user` - The user we will send E-mail to.
- `bool $shouldSendEmail` - Sends E-mail when true.

```php
UserService::sendWhenSetToResetPW($user, true);
```

<br>

### H. `toggleAccountStatus()`
Toggles the `inactive` status based on request input. Returns `true` if the account was just deactivated.

Parameters:
- `Users $user` - The user whose status we want to set.
- `Input $request` - The request.
- `int|null $currentInactive` - Value of $user->inactive before post.

Returns:
- `bool` - True if we want to send mail and otherwise false.

```php
$shouldEmail = UserService::toggleAccountStatus($user, $request, $previousInactive);
```

<br>

### I. `toggleResetPassword()`
Toggles the `reset_password` flag based on request input. Returns `true` if it was just activated.

Parameter:
- `Users $user` - The user whose status we want to set.
- `Input $request` - The request.
- `int|null $currentReset` - Value of $user->reset_password before post.

Returns: 
- `int` - `1` if reset_password is `on`, otherwise we return `0`.

```php
$shouldEmail = UserService::toggleResetPassword($user, $request, $previousReset);
```

<br>

## 3. Related Components<a id="related-components"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- `AuthService` – Used to validate current user identity and confirm password fields.
- `ProfileImages` – Handles image persistence, deletion, and sorting.
- `Uploads` – File upload handler.
- `AccountDeactivatedMailer / PasswordResetMailer` – Responsible for user notification emails.
- `Users` – Model representing application users.

<br>

## 4. Examples <a id="Examples"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

### A. Password Reset
You can use the `UserService::toggleResetPassword` and `UserService::sendWhenSetToResetPW` to send an E-mail to a user when an administrator sets the `reset_password` field for an account.  An example is shown below:

```php
public function setResetPasswordAction($id) {
    $user = Users::findById((int)$id);
    $resetPW = $user->reset_password;
    DashboardService::checkIfCurrentUser($user);

    if($this->request->isPost()) {
        $this->request->csrfCheck();
        $user->assign($this->request->get(), Users::blackListedFormKeys);
        $shouldSendEmail = UserService::toggleResetPassword($user, $this->request, $resetPW);
        if($user->save()) {
            UserService::sendWhenSetToResetPW($user, $shouldSendEmail);
            redirect('admindashboard.details', [$user->id]);
        }
    }

    $this->view->user = $user;
    $this->view->displayErrors = $user->getErrorMessages();
    $this->view->postAction = route('admindashboard.setResetPassword', [$user->id]);
    $this->view->render('admindashboard.set_reset_password', true, true);
}
```

The `toggleResetPassword` function manages the user's `reset_password` field and returns true if a password reset E-mail should be sent.  The `sendWhenSetToResetPW` function creates an event for sending the E-mail only if `$shouldSendEmail` is true.  For example, when the administrator removes the `reset_password` status for a user.

<br>

### B. Account Deactivation
Below is an example for sending an E-mail when the administrator deactivates an account:
```php
public function setStatusAction($id) {
    $user = Users::findById((int)$id);
    $inactive = $user->inactive;
    DashboardService::checkIfCurrentUser($user);

    if($this->request->isPost()) {
        $this->request->csrfCheck();
        $user->assign($this->request->get(), Users::blackListedFormKeys);
        $shouldSendEmail = UserService::toggleAccountStatus($user, $this->request, $inactive);
        if($user->save()) {
            UserService::sendWhenSetToInactive($user, $shouldSendEmail);
            redirect('admindashboard.details', [$user->id]);
        }
    }

    $this->view->user = $user;
    $this->view->displayErrors = $user->getErrorMessages();
    $this->view->postAction = route('admindashboard.setStatus', [$user->id]);
    $this->view->render('admindashboard.set_account_status', true, true);
}
```

Just like above we follow a similar two step process.  We toggle the `active` and `login_attempts` fields and send the email after `save` when appropriate.

<br>

## 4. Notes <a id="notes"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- Admin users (`["Admin"]` ACL) are protected from deletion.
- Upload handling assumes that `$_FILES['profileImage']` is present for Uploads.
- Email methods like `sendWhenSetToInactive()` and `sendWhenSetToResetPW()` rely on `AccountDeactivatedMailer` and `PasswordResetMailer` respectively.
- `toggleAccountStatus()` and `toggleResetPassword()` help controllers determine if emails should be triggered post-form submission.
