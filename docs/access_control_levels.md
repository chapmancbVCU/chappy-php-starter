<h1 style="font-size: 50px; text-align: center;">Access Control Levels (ACLs)</h1>

## Table of contents
1. [Overview](#overview)
2. [How ACLs Work](#how-it-works) 
3. [acl.json File](#acl-file)
4. [Controllers and Views](#controllers-and-views)
5. [Developer Checklist: Adding a New ACL Role](#checklist)
6. [See Also](#see-also)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Chappy.php supports **Access Control Levels (ACLs)** to manage which users can access specific controllers and actions. ACLs are part of a secure application architecture, aligning with the **CIA Triad** of information security:

- **Confidentiality** – Only authorized users can access specific data or routes.  
- **Integrity** – Data is protected from unauthorized modification or deletion.  
- **Availability** – Authorized users can access data and features without unnecessary restriction.

ACLs in this framework are configured using the `acl.json` file in the `app/` directory and the `acl` field in the users table (stored as a JSON-encoded array).

<br>

## 2. How ACLs Work <a id="how-it-works"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
When a user is assigned a session, their default access level is `"Guest"`. If they log in or their role is elevated (e.g., admin), their ACL changes to `"LoggedIn"` or `"Admin"`.

The system resolves access by checking:

1. **If the access level exists in `acl.json`**
2. **If the controller is allowed or denied for that role**
3. **If the action is specifically permitted or denied**

If the access check fails, the user is redirected to a restricted access view.

<br>

## 3. acl.json File <a id="acl-file"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `acl.json` file maps ACL roles (e.g., Guest, LoggedIn, Admin) to controllers and actions. Example:

```json
{
    "Guest" : {
        "denied" : {},
        "Home" : ["*"],
        "Auth" : ["login", "register", "resetPassword"],
        "Restricted" : ["*"]
    },
    "LoggedIn" : {
        "denied" : {
            "Auth" : ["login", "register", "resetPassword"]
        },
        "Auth" : ["logout"],
        "Contacts" : ["*"],
        "Profile" : ["*"]
    },
    "Admin" : {
        "denied" : {},
        "Admindashboard" : ["*"]
    }
}
```

Let's begin by looking at the Guest object in this json file.  Any user with an access level of guest can have access to certain areas associated with the Home, Auth, and Restricted controllers.  When a user starts a session, their default access will be `Guest` and will be over written by any other ACLs based on whether or not they achieve a LoggedIn status or the user has administration privileges.

Looking specifically at `Home` you will see an array where its only element is an asterisk (*).  Anybody with Guest access level can utilize actions within the HomeController.  The line with the value `Auth` contains and array containing actions that are available to unauthenticated users.  In this case, we want the user to be able to login, register, and reset their password.

The LoggedIn object is a different such that it contains a denied object that has information regarding what an authenticated user can't access.  It makes sense that a user who is already authenticated shall not be able to access the login, resetPassword, and register views.  If the user wishes to add a change password view it will be up to them to decide where in the json file to add the action name.  If the user wishes to add it to the `Auth` controller the Auth object within LoggedIn shall be updated as follows:

```json
"LoggedIn" : {
        "denied" : {
            "Auth" : ["login", "register", "resetPassword"]
        },
        "Auth" : ["logout", "changePassword"],
        "Contacts" : ["*"],
        "Profile" : ["*"]
    },
```
<br>

💡 Key Concepts:
- "*" means all actions in that controller are allowed.
- "denied" can override permissions for specific actions.

🛠 Example: Adding a changePassword Action
To allow "LoggedIn" users to change their password, update the ACL:
```json
"LoggedIn": {
  "denied": {
    "Auth": ["login", "register", "resetPassword"]
  },
  "Auth": ["logout", "changePassword"],
  "Contacts": ["*"],
  "Profile": ["*"]
}
```

<br>

## 4. Controllers and Views <a id="controllers-and-views"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
After adding the name of the action they wish to create, in this case `changePassword`, the user will need to create the view and add a function to the AuthController called `changePasswordAction`.  An example is shown below:

```php
public function changePasswordAction(int $id): void {
    $user = Users::findById((int)$id);

    // function logic
    // ...

    // Configure the view
    $this->view->displayErrors = $acl->getErrorMessages();
    $this->view->postAction = Env::get('APP_DOMAIN', '/') . 'auth' . DS . 'changePassword';
    $this->view->render('auth/change_password');
}
```

In the configure the view section it is best practice to set postAction to the same name as changePassword.  The last line contains a call to the render function of the View class.  It follows the syntax of `directory_name/view_name.`  You do not need to add .php to the end of the view name.  More information about views, layouts, and components can be found [here](views).

Finally, we have the `Auth` object.  For the base project, it is the only other ACL that is set in the acl table.  Every time you add a new ACL you will need to configure it in the acl.json file.

🔧 Best Practice:
Use the same name for:
- the view file: resources/views/auth/change_password.php
- the controller method: changePasswordAction
- the form action URL
- This makes routing and debugging easier.

<br>

## 5. ✅ Developer Checklist: Adding a New ACL Role <a id="checklist"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
1. Add the new role to the acl.json file
2. Add matching permissions (controllers/actions)
3. Set the role name in the user table (acl column)
4. Create any new views or controllers needed
5. Test access control flow

<br>

## 6. 🔗 See Also <a id="see-also"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- [User Registration and Authentication](user_registration_and_authentication)
- [Views](views)
- [Session and Flash Messages](session_and_flash_messages)
