<h1 style="font-size: 50px; text-align: center;">Administration</h1>

## Table of contents
1. [Overview](#overview)
2. [Administration Dashboard](#admin-dashboard)
3. [Administration Menu](#admin-menu)
4. [Profile Details](#profile-details)
5. [Edit Details](#edit-details)
6. [Reset Password](#reset-password)
7. [Account Status](#account-status)
8. [Delete Account](#delete-account)
9. [Manage Access Control Levels (ACLs)](#manage-acls)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Administration features can be accessed by any user who belongs to the admin group.  This frame work supports the ability for administrators to perform the following tasks:
1. Access the administration dashboard
2. View list of all users
3. View and edit user profiles (useful if a user violates any terms of service agreements)
4. Set the reset and unset the reset password flag for a user
5. Activate or deactivate users
6. Delete users
7. Manage Access Control Levels (ACLs)
<br>

## 2. Administration Dashboard <a id="admin-dashboard"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The administration dashboard is easily accessible by any user in the admin group by clicking on the `Admin` link in the navigation panel.  Once at the Administration Dashboard the user is presented with a list of all users.  An example of the Administration Dashboard view is shown below in figure 1:

<div style="text-align: center;">
  <img src="assets/admin-dashboard-index.png" alt="Administration dashboard index view">
  <p style="font-style: italic;">Figure 1 - Administration dashboard index view</p>
</div>
<br>

## 3. Administration Menu <a id="admin-menu"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
When using the administration features the user has access to the Administration Menu in the navigation bar.  It contains links to the Administration Dashboard and a view for managing ACLs.  An image of the Administration Menu is shown in figure 2.

<div style="text-align: center;">
  <img src="assets/admin-menu.png" alt="Administration navbar menu">
  <p style="font-style: italic;">Figure 2 - Administration navbar menu</p>
</div>
<br>

## 4. Profile Details <a id="profile-details"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
When you click in the details button for a user you are sent to the administrator's profile view for a particular user.  It is similar to the view you get when visiting your own profile but includes additional features for password reset, account status, and account deletion.  You also see other details such as number of login attempts since the last successful login, status of password reset flag for the user, creation and update date information, and account status.  An example of this view is shown in figure 3.

<div style="text-align: center;">
  <img src="assets/admin-profile-details.png" alt="Administration view of profile">
  <p style="font-style: italic;">Figure 3 - Administration view of profile</p>
</div>
<br>

## 5. Edit Details <a id="edit-details"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The edit details view is also similar to the equivalent view for the user profile details view.  The difference is administrators have the ability to manage Access Control Levels (ACLs) for a user.  An example of this view is shown below in figure 4.

<div style="text-align: center;">
  <img src="assets/admin-edit-profile.png" alt="Administration edit profile view">
  <p style="font-style: italic;">Figure 4 - Administration edit profile view</p>
</div>

Take note of the Manage ACLs section in the figure above.  It is represented as a checkbox group to allow multiple access control levels for individual users.  Learn more about ACLs [here](access_control_levels)
<br>

## 6. Reset Password <a id="reset-password"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)
This view has a form with a checkbox that enables administrators to set or unset the reset_password field for a particular user.
<br>

## 7. Account Status <a id="account-status"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)
This view has a form with a checkbox that enables administrators to activate or deactivate an account.  Accounts get deactivated after a user exceeds a number of allowed login attempts that the administrator is able to set in the project's configuration file.  The administrator can also deactivate an account at anytime using the form found in this view.
<br>

## 8. Delete Account <a id="delete-account"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)
Administrators have permission to delete an account.  The database uses a soft delete feature that removes the delete account for all listings without actually removing the record.
<br>

## 9. Manage Access Control Levels (ACLs) <a id="manage-acls"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)
This view provides the ability to manage ACLs that are available to this framework.  More about ACLs can be found in the [ACLs](access_control_levels) section of the user guide.