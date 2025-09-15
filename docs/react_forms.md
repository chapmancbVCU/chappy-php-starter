<h1 style="font-size: 50px; text-align: center;">React Forms</h1>

## Table of contents
1. [Overview](#overview)
2. [Setup](#setup)
3. [Buttons](#buttons)
4. [Elements](#elements)

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

To add the optional Error Bag add the `<Forms.DisplayErrors />` component.  Make sure you pass in the errors prop. The prop name `error` before the `=` sign must be spelled exactly as in the example.

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

**Add Your Elements and Buttons**
Add your elements and buttons by using supplied components.  Elements, buttons, and submit button components are described in the following sections.

Example:
```jsx
function Register({ users, errors }) {
    return (
        <form action="" className="form" method="post" encType="multipart/form-data">
            <Forms.CSRF />
            <Forms.Input 
                type="text"
                label="User name"
                name="username"
                value={user.username}
                inputAttrs={{className: 'form-control input-sm'}}
                divAttrs={{className: 'form-group mb-3'}}
                errors={errors}
            />
            
            ...

            <Forms.SubmitBlock
                label="Register"
                inputAttrs={{className: 'btn btn-large btn-primary'}}
                divAttrs={{className: 'text-end'}}
            />
        </form>
    )
}
```

Many components come with common prop names such as label, name, value, inputAttrs, divAttrs, and the optional error prop.  Use error prop if you want validation message displayed below any field.

Prop names for each component is described for the corresponding sections below.

<br>

## 3. Buttons <a id="buttons"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

**`Button`**

Returns Button component with text set.  Supports ability to have functions for event handlers

Prop names:
- `{string} label` - The contents of the button's label.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.

Example:
```jsx
<Forms.Button 
    label="My Button" 
    inputAttrs={{className: 'btn btn-large btn-primary'}} 
/>
```

<br>

**`ButtonBlock`**

Supports ability to create a styled button and styled surrounding div block.  Supports ability to have functions for event handlers.

Prop names:
- `{string} label` - The contents of the button's label.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.

Example:
```jsx
<Forms.ButtonBlock 
    label="My Button" 
    inputAttrs={{className: 'btn btn-large btn-primary'}}
    divAttrs={{className: 'text-end'}}
/>
```

<br>

**`SubmitBlock`**

Generates a div containing an input of type submit.

Prop names:
- `{string} label` - The contents of the button's label.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.

Example:
```jsx
<Forms.SubmitBlock 
    label="Submit" 
    inputAttrs={{className: 'btn btn-large btn-primary'}}
    divAttrs={{className: 'text-end'}}
/>
```

<br>

**`SubmitTag`**

Create a input element of type submit.

Prop names:
- `{string} label` - The contents of the button's label.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.

Example:
```jsx
<Forms.SubmitTag 
    label="Submit" 
    inputAttrs={{className: 'btn btn-large btn-primary'}}
    divAttrs={{className: 'text-end'}}
/>
```

<br>

## 4. Elements <a id="elements"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

**`CheckBoxLeftLabel`**

Generates a div containing an input of type checkbox with the label to the left.

Prop names:
- `{string} label` - Sets the label for this input.
- `{string} name` - Sets the value for the name, for, and id attributes for this input.
- `{string} value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `{bool} checked` - The value for the checked attribute.  If true this attribute will be set as checked="checked".  The default value is false.  It can be set with values during form validation and forms used for editing records.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.

Example:
```jsx
<Forms.CheckBoxLeftLabel
    label="Remember Me"
    name="remember_me"
    value="on"
    checked={rememberMeChecked}
    divAttrs={{className: 'form-group mb-3'}}
/>
```

<br>

**`CheckBoxLeftRight`**

Generates a div containing an input of type checkbox with the label to the right.

Prop names:
- `{string} label` - Sets the label for this input.
- `{string} name` - Sets the value for the name, for, and id attributes for this input.
- `{string} value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `{bool} checked` - The value for the checked attribute.  If true this attribute will be set as checked="checked".  The default value is false.  It can be set with values during form validation and forms used for editing records.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.

Example:
```jsx
<Forms.CheckBoxLeftLabel
    label="Remember Me"
    name="remember_me"
    value="on"
    checked={rememberMeChecked}
    divAttrs={{className: 'form-group mb-3'}}
/>
```

<br>

**`CSRF`**

Generates hidden component for csrf token.

Prop name:
- `{string} name` - The name for the csrf token. 

Example:
```jsx
<Forms.CSRF />
```

<br>

**`Email`**

Renders an HTML div element that surrounds an input of type email.

Prop names:
- `{string} label` - Sets the label for this input.
- `{string} name` - Sets the value for the name, for, and id attributes for this input.
- `{string} value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.

Example:
```jsx
<Forms.Email 
    label="Email"
    name="email"
    value={user.email}
    inputAttrs={{className: 'form-control input-sm', placeholder: 'joe@example.com'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```

<br>

**`Hidden`**

Generates a hidden input element.

Prop names:
- `{string} name` - The value for the name and id attributes.
- `{string|number}` - value The value for the value attribute.

Example:
```jsx
<Forms.Hidden
    name="hidden_name"
    value={myValue}
/>
```

<br>

**`Input`**

Assists in the development of forms input blocks in forms.  It accepts parameters for setting attribute tags in the form section.  Not to be used for inputs of type "Submit"  For submit inputs use the submitBlock or submitTag functions.

Prop names:
- `{'color'|'date'|'date-local'|'email'|'file'|'month'|'number'|'password'|'range'|'search'|'tel'|'text'|'time'|'url'|'week'} [type='text']` - The input type we want to generate.
- `{string} label` - Sets the label for this input.
- `{string} name` - Sets the value for the name, for, and id attributes for this input.
- `{string} value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.

Example:
```jsx
<Forms.Input 
    type="text"
    label="User name"
    name="username"
    value={user.username}
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```

<br>

**`Output`**

Generates an HTML output element.

Prop names:
- `{string} name` - Sets the value for the name attributes for this input.
- `{string} forAttr` - Sets the value for the for attribute.

Example:
```jsx
<Forms.Output 
    name="my-name"
    forAttr="for-attr-value"
/>
```

<br>

**`Radio`**

Creates an input element of type radio with an accompanying label element.  Compatible with radio button groups.

Prop names:
- `{string} label` - Sets the label for this input.
- `{string} id` - The id attribute for the radio input button.
- `{string} name` - Sets the value for the name attribute.
- `{string} value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `{bool} checked` - The value for the checked attribute.  If true this attribute will be set as checked="checked".  The default value is false.  It can be set with values during form validation and forms used for editing records.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.

Example:
```jsx
<Forms.Radio 
    label="HTML"
    id="html"
    name="fav_language"
    value="HTML"
    checked={test1Var}
    inputAttrs={{className: 'form-group me-1'}}
/>
<Forms.Radio 
    label="CSS"
    id="css"
    name="fav_language"
    value="CSS"
    checked={test2Var}
    inputAttrs={{className: 'form-group me-1'}}
/>
```

<br>

**`RichText`**

Rich text editor field (TinyMCE) that posts HTML via a hidden input.

Behavior:
- Decodes any HTML entities in `value` before seeding the editor (so `<p>` renders as a paragraph).
- Uses TinyMCE as an uncontrolled editor; current HTML is mirrored into a hidden `<input name={name}>` so your PHP controller receives `$_POST[name]` as HTML.
- UI skin CSS is expected to be imported elsewhere; `skin:false` prevents URL fetches.

Prop names:
- `{string} label` - Sets the label for this input.
- `{string} name` - Sets the value for the name, for, and id attributes for this input.
- `{string} value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.

Example:
```jsx
<Forms.RichText
    label="Description"
    name="description"
    value={user.description || ""}
    inputAttrs={{ placeholder: 'Describe yourself here...' }}
    divAttrs={{ className: 'form-group mb-3' }}
/>
```

<br>

**`Select`**

Renders a select element with a list of options.

Prop names:
- `{string} label` - Sets the label for this input.
- `{string} name` - Sets the value for the name, for, and id attributes for this input.
- `{string} value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `{string} fieldName` - The name of the field in the model to use.
- `{array} options` - The list of options we will use to populate the 
 * select option dropdown.  The default value is an empty array.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.

Example:
```jsx
<Forms.Select 
    label="Example"
    name="options"
    value={selectedVal}
    fieldName="name"
    options={options}
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```