<h1 style="font-size: 50px; text-align: center;">React Forms</h1>

## Table of contents
1. [Overview](#overview)
2. [Setup](#setup)



<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
We support React tailored form helper similar to the `Core\FormHelper` for generating form elements, setting attributes, and presenting server side errors.

Setup by adding following line to your `.jsx` component:
```jsx
import Forms from "@chappy/components/Forms";
```

<br>

## 2. Setup <a id="setup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
To get started, import the Forms module, pass in your props, and import the Forms module.

```jsx
import React from "react";
import Forms from "@chappy/components/Forms";
import {PasswordComplexityRequirements} from '@chappy/components/PasswordComplexityRequirements';

function Register({ users, errors }) {
    return (
        <form action="" className="form" method="post" encType="multipart/form-data">
        </form>
    )
}
```

**CSRF**

Every form needs a CSRF token to prevent CSRF attacks.  Add the `<Forms.CSRF />` component to the top of your form.
```jsx
    <form action="" className="form" method="post" encType="multipart/form-data">
        <Forms.CSRF />
    </form>
```

**Error Bag**

To add the optional Error Bag add the `<Forms.DisplayErrors />` component.  Make sure you pass in the errors prop. The word `error` before the `=` sign must be spelled exactly as in the example.

Example:
```jsx
function Register({ users, errors }) {
    return (
        <form action="" className="form" method="post" encType="multipart/form-data">
            <Forms.CSRF />
            <Forms.DisplayErrors errors={errors}/>
        </form>
    )
}
```