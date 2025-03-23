<h1 style="font-size: 50px; text-align: center;">User Registration and Authentication</h1>

## Table of contents
1. [Overview](#overview)
2. [Registration](#registration)
3. [Login](#login)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This section provides an overview of the registration and authentication process.
<br>

## 2. Registration <a id="registration"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
At the login page the user can access the registration page. A screenshot is shown below in figure 1.

<div style="text-align: center;">
  <img src="assets/registration.png" alt="Registration view">
  <p style="font-style: italic;">Figure 1 - Registration view</p>
</div>

Field Descriptions:
1. First Name: User's first name and it's a required field.
2. Last Name: User's last name and it's a required field.
3. E-mail: User's Email and it's a required field
4. User name: Your user name and it's a required field
5. Description: Optional field where the user can describe themselves.
6. Profile Image: Optional profile image.
7. Password: Make sure they match and fulfill complexity requirements.
<br>

## 3. Login <a id="login"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The login page is the standard login setup with a remember me checkbox. Once you login you can access parts of the application that are allowed for your access control level. The number of failed attempts allowed can be set by your administrator. If you are an administrator and you get locked out and there are no other administrators you can set the inactive field to 0 and login_attempts to 0 in the record associated with your user account.