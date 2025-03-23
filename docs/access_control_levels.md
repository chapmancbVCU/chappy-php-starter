<h1 style="font-size: 50px; text-align: center;">Access Control Levels (ACLs)</h1>

## Table of contents
1. [Overview](#overview)
2. [acl.json File](#acl-file)
3. [Controllers and Views](#controllers-and-views)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework supports Access Control Levels (ACLs) to manage permissions.  A solid permissions structure is a fundamental part of what we call in the IT industry Confidentiality, Integrity, and Availability (CIA) triad of information security.  CIA is defined as follows:
1. Confidentiality - Only those who are allowed access shall be able to access certain data.
2. Integrity - The data shall be protected.  This includes anything from accidental deletion to protecting the data from events such as fire and severe weather events.  Many systems have data duplicated in multiple locations in order to provide sufficient protection.
3. Availability - Those who have permission to access the data shall be able to access it without impedient.  Once the user no longer needs access to the data permissions shall be removed.

We handle access control using a combination of a acl.json file and a acl field in the users table which is represented as an array.
<br>

## 2. acl.json File <a id="acl-file"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The acl.json file located under `app/` allows users to grant or deny access to based on controllers and actions.  The acl.json file for the base project is shown below:

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

## 3. Controllers and Views <a id="controllers-and-views"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
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