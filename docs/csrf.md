<h1 style="font-size: 50px; text-align: center;">Administration</h1>

## Table of contents
1. [Overview](#overview)


<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework supports Cross-Site Request Forgery (CSRF) protection.  Every time a new session is started a new CSRF token is generated.  It is advised that users utilize CSRF checks for all actions associated with forms.  You can implement CSRF checks on all form submissions for submitting and deleting data.