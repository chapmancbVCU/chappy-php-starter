<h1 style="font-size: 50px; text-align: center;">React Forms</h1>

## Table of contents
1. [Overview](#overview)
2. [Setup](#setup)
3. [Buttons](#buttons)
4. [CheckBoxLeftLabel()](#CheckBoxLeftLabel)
5. [CheckBoxRightLabel()](#CheckBoxRightLabel)
6. [CheckboxGroup()](#CheckboxGroup)
7. [CSRFInput()](#CSRFInput)
8. [CSRFToken()](#CSRFToken)
9. [datalists](#datalists)
    * A. [DataListBlock()](#DataListBlock)
    * B. [DataListColor()](#DataListColor)
    * C. [DataListDate()](#DataListDate)
    * D. [DataListDateTimeLocal()](#DataListDateTimeLocal)
    * E. [DataListInterval()](#DataListInterval)
    * F. [DataListMonth()](#DataListMonth)
    * G. [DataListText()](#DataListText)
    * H. [DataListTime()](#DataListTime)
    * I. [DataListWeek()](#DataListWeek)
10. [DisplayErrors()](#DisplayErrors)
11. [FieldErrors()](#FieldErrors)
12. [Hidden()](#hidden)
13. [Input Components](#input-components)
    * A. [Input()](#Input)
    * B. [Color()](#Color)
    * C. [Confirm()](#Confirm)
    * D. [Currency()](#Currency)
    * E. [DateSelector()](#DateSelector)
    * F. [DateTimeLocal()](#DateTimeLocal)
    * G. [Email()](#Email)
    * H. [fileSelector()](#fileSelector)
    * I. [Image()](#Image)
    * J. [ImageBlock()](#ImageBlock)
    * K. [Interval()](#Interval)
    * L. [Month()](#Month)
    * M. [Number()](#Number)
    * N. [Password()](#Password)
    * O. [Search()](#Search)
    * P. [Tel()](#Tel)
    * Q. [text()](#text)
    * R. [textArea](#textArea)
    * S. [Time()](#Time)
    * T. [urlInput()](#urlInput)
    * U. [week()](#week)
14. [Output()](#Output)
15. [Radio Buttons](#radio)
16. [RememberMe()](#RememberMe)
17. [RichText()](#RichText)
18. [Select()](#Select)
19. [submitBlock()](#submitBlock)
20. [SubmitTag()](#SubmitTag)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#tale-of-contents)</span>
We spport React tailored form helper similar to the `Core\FormHelper` for generating form elements, setting attrbutes, and presenting server side errors.
Setup by adding following line to your `.jsx` component:
``jsx
mport Forms from "@chappy/components/Forms;
``<br>
# 2. Setup <a id="setup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#tabl-of-contents)</span>
To getstarted, import the Forms module, pass in your props, and import the Forms module.
```jsx
import React from "react";
import Forms from "@chappy/components/Forms";
import {PasswordComplexityRequirements} from '@chappy/components/PasswordComplexityRequirements';

function Register({ users, errors }) {
   return (
       <form action="" className="form" method="post" encType="multipart/form-data">
       </form>
   )

``
<br>
**CSRF**
Every form needs a CSRF token to prevent CSRF attacks.  Add the `<Forms.CSRF />` component to the top of your form.
``jsx
   <form action="" className="form" method="post" encType="multipart/form-data">
       <Forms.CSRFInput />
   </form>
```

<br>

**Error Bag**

To add the optional Error Bag add the `<Forms.DisplayErrors />` component.  Make sure you pass in the errors prop. The prop name `error` before the `=` sign must be spelled exactly as in the example.

Example:
```jsx
function Register({ users, errors }) {
    return (
        <form action="" className="form" method="post" encType="multipart/form-data">
            <Forms.CSRFInput />
            <Forms.DisplayErrors errors={errors}/>
        </form>
    )
}
```

<br>

**Add Your Elements and Buttons**
Add your elements and buttons by using supplied components.  Elements, buttons, and submit button components are described in the following sections.

Example:
{% raw %}
```jsx
function Register({ users, errors }) {
    return (
        <form action="" className="form" method="post" encType="multipart/form-data">
            <Forms.CSRFInput />
            <Forms.Text 
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
{% endraw %}

Many components come with common prop names such as label, name, value, inputAttrs, divAttrs, and the optional error prop.  Use error prop if you want validation message displayed below any field.

Prop names for each component is described for the corresponding sections below.

<br>

## 3. Buttons <a id="buttons"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

**`Button = ({})`**

Returns Button component with text set.  Supports ability to have functions for event handlers

Prop names:
- `{string} label` - The contents of the button's label.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.

Example:
{% raw %}
```jsx
<Forms.Button 
    label="My Button" 
    inputAttrs={{className: 'btn btn-large btn-primary'}} 
/>
```
{% endraw %}

<br>

**`ButtonBlock = ({})`**

Supports ability to create a styled button and styled surrounding div block.  Supports ability to have functions for event handlers.

Prop names:
- `{string} label` - The contents of the button's label.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.

Example:
{% raw %}
```jsx
<Forms.ButtonBlock 
    label="My Button" 
    inputAttrs={{className: 'btn btn-large btn-primary'}}
    divAttrs={{className: 'text-end'}}
/>
```
{% endraw %}

<br>

## 4. `CheckBoxLeftLabel = ({})` <a id="CheckBoxLeftLabel"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates a div containing an input of type checkbox with the label to the left.

Prop names:
- `{string} label` - Sets the label for this input.
- `{string} name` - Sets the value for the name, for, and id attributes for this input.
- `{string} value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `{bool} checked` - The value for the checked attribute.  If true this attribute will be set as checked="checked".  The default value is false.  It can be set with values during form validation and forms used for editing records.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.
- `{Record<string, string[]>|string[]} [errors=[]]` - The errors object.  Default value is an empty object.

Example:
{% raw %}
```jsx
<Forms.CheckBoxLeftLabel
    label="Remember Me"
    name="remember_me"
    value="on"
    checked={rememberMeChecked}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

## 5. `CheckBoxRightLabel = ({})` <a id="CheckBoxRightLabel"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates a div containing an input of type checkbox with the label to the right.

Prop names:
- `{string} label` - Sets the label for this input.
- `{string} name` - Sets the value for the name, for, and id attributes for this input.
- `{string} value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `{bool} checked` - The value for the checked attribute.  If true this attribute will be set as checked="checked".  The default value is false.  It can be set with values during form validation and forms used for editing records.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.
- `{Record<string, string[]>|string[]} [errors=[]]` - The errors object.  Default value is an empty object.

Example:
{% raw %}
```jsx
<Forms.CheckBoxRightLabel
    label="Remember Me"
    name="remember_me"
    value="on"
    checked={rememberMeChecked}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

## 6. `CheckboxGroup = ({})` <a id="CheckboxGroup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Renders a group of checkboxes sharing one name (submitted as name[]), one wrapping div, and ONE error span. Label-right per box.

Example:
{% raw %}
```jsx
<Forms.CheckboxGroup 
    name='acls'
    label='acls
    options={acls}
    value={userAcls}
    divAttrs={{className: 'form-check'}}
/>
```
{% endraw %}

Prop names:
- `{string} name` - Group name WITHOUT '[]' (added internally), e.g. 'acls'.
- `{object} options` - The list of options we will use to populate the radio group.  The default value is an empty object.
- `{array} selectedValues` - Values that should render checked (the current set).
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.
- `{Record<string,string[]>|string[]} [errors=[]]` - The errors object.  Default value is an empty object.

Returns:
- `{HTMLDivElement}` - A surrounding div and the accompanying nested group of checkbox inputs.

<br>

## 7. `CSRFInput = ({})` <a id="CSRFInput"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates hidden component for csrf token.

Example:
```jsx
<Forms.CSRFInput />
```

Prop name:
- `{string} name` - The name for the csrf token. 

Returns:
- `{HTMLInputElement}` - Input element of type hidden containing csrf token.

<br>

## 8. `CSRFToken = ({})` <a id="CSRFToken"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns CSRF token that is used when a form is submitted.

Example: 
```jsx
Forms.CSRFToken(e)
```

Prop name:
- `{Event} e` - The event associated with a form submission.

Returns:
- `{string}` - The CSRF token string.

<br>

## 9. `datalists = ({})` <a id="datalists"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This section contains a listing of input functions paired with a datalist for suggestions.

Prop names common among all of these functions are:
- `{string} label` - Sets the label for this input.
- `{string} name` - Sets the value for the name, for, and id attributes for this input.
- `{string} listName` - The list name  and id for the datalist element.
- `{string|number} value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `{array} options` - A list of suggestions.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.
- `{Record<string, string[]>|string[]} [errors=[]]` - The errors object.  Default value is an empty object.

<br>

### A. `DataListBlock()` <a id="DataListBlock">
The parent of all datalist components.  

It accepts an additional prop:
- `{string}` - type The input type.

Returns:
- `{HTMLDListElement}` - A surrounding div and the input element.

<br>

### B. `DataListColor()` <a id="DataListColor">
Renders an HTML div element that surrounds an input of type color with an accompanying datalist of suggestions.

Example:
{% raw %}
```jsx
<Forms.DataListColor
    label="Choose a color"
    name="color"
    listName="colors"
    value=""
    options={['#800000', '#8B0000', '#A52A2A', '#DC143C']}
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### C. `DataListDate()` <a id="DataListDate">
Renders an HTML div element that surrounds an input of type date with an accompanying datalist of suggestions.

Example:
{% raw %}
```jsx
<Forms.DataListDate
    label="Choose a date"
    name="date"
    listName="dates"
    value=""
    options={['2026-10-07', '2026-10-08', '2026-10-09', '2026-10-10']}
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### D. `DataListDateTimeLocal()` <a id="DataListDateTimeLocal">
Renders an HTML div element that surrounds an input of type datetime-local with an accompanying datalist of suggestions.

Example:
{% raw %}
```jsx
<Forms.DataListDateTimeLocal
    label="Start date:"
    name="date"
    listName="dates"
    value=""
    options={['2026-10-07T14:05', '2026-10-08T06:44', '2026-10-09T20:20', '2026-10-1009:56']}
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### E. `DataListInterval()` <a id="DataListInterval">
Renders an HTML div element that surrounds an input of type range with an accompanying datalist of suggestions.

Example:
{% raw %}
```jsx
const options = {
    0: 'Low',
    10: 'Fair',
    20: 'Medium',
    25: 'Good',
    45: 'High'
};

<Forms.DataListInterval
    label="Tip amount:"
    name="tick"
    listName="tickmarks"
    min={0}
    max={45}
    value=""
    options={options}
    divAttrs={{className: 'form-group mb-3 d-flex flex-column'}}
/>
```
{% endraw %}

<br>

### F. `DataListMonth()` <a id="DataListMonth">
Renders an HTML div element that surrounds an input of type month with an accompanying datalist of suggestions.

Example:
{% raw %}
```jsx
<Forms.DataListMonth
    label="Select a month:"
    name="month"
    listName="months"
    value=""
    options={['2026-09', '2026-10', '2026-11', '2026-12']}
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### G. `DataListText()` <a id="DataListText">
Renders an HTML div element that surrounds an input of type text with an accompanying datalist of suggestions.

Example:
{% raw %}
```jsx
<Forms.DataListText
    label="Select an animal:"
    name="animal"
    listName="animals"
    value=""
    options={['Cat', 'Dog', 'Cow', 'Fish']}
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### H. `DataListTime()` <a id="DataListTime">
Renders an HTML div element that surrounds an input of type time with an accompanying datalist of suggestions.

Example:
{% raw %}
```jsx
<Forms.DataListTime
    label="Select a time:"
    name="time"
    listName="times"
    value=""
    options={['12:00', '13:00', '14:00', '15:00']}
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### I. `DataListWeek()` <a id="DataListWeek">
Renders an HTML div element that surrounds an input of type week with an accompanying datalist of suggestions.

Example:
{% raw %}
```jsx
const options = {
    min: '2026-32',
    max: '2026-36'
};

<Forms.DataListWeek
    label="Select a week:"
    name="week"
    listName="weeks"
    value=""
    options={options}
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}
<br>

## 10. DisplayErrors <a id="DisplayErrors"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#tale-of-contents)</span>
Creates an error bag containing all existing errors.

Prop name:
- `{object}` - errors Object containing errors.

Returns:
- `{HTMLDivElement}` - The error bag.

<br>

## 11. FieldErrors <a id="FieldErrors"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#tale-of-contents)</span>
Renders an error message for a particular form field.

Prop names:
- `{object} errors` - The error object.
- `{string} name` - Used to search errors object for key/form field.

Returns:
- `{HTMLSpanElement}` - The error message for a particular field.

<br>

## 12. Hidden <a id="Nidden"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#tale-of-contents)</span>
Generates a hidden input element.

Example:
```jsx
<Forms.Hidden
    name="hidden_name"
    value={myValue}
/>
```

Prop names:
- `{string} name` - The value for the name and id attributes.
- `{string|number}` - value The value for the value attribute.

Returns:
- `{HTMLInputElement}` The html input element with type hidden

<br>

## 13. Input Components <a id="input-components"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#tale-of-contents)</span>
This section contains a listing of input functions that are wrappers for the HTML input element.

Prop names common among all of these functions are:
- `{string} label` - Sets the label for this input.
- `{string} name` - Sets the value for the name, for, and id attributes for this input.
- `{string} listName` - The list name  and id for the datalist element.
- `{string|number} value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.
- `{Record<string, string[]>|string[]} [errors=[]]` - The errors object.  Default value is an empty object.

<br>

### A. Input <a id="input">
The parent of all input components.  

Example:
{% raw %}
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
{% endraw %}

It accepts an additional prop:
- `{string}` - type The input type.

Returns:
- `{HTMLDivElement}` - A surrounding div and the input element.

<br>

### B. Color <a id="Color">
Assists in the development of input of type color in forms.  It accepts parameters for setting  attribute tags in the form section.

Example:
{% raw %}
```jsx
<Forms.Color
    label="Choose a color"
    name="color"
    listName="colors"
    value=""
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### C. Confirm <a id="Confirm">
Assists in the development of input of type text in forms.  It accepts parameters for setting attribute tags in the form section.

Example:
{% raw %}
```jsx
<Forms.Confirm 
    label="Confirm Password"
    name="confirm"
    value={user.confirm}
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### D. Currency <a id="Currency">
Renders an HTML div element that surrounds an input of type text for currency.

Example:
{% raw %}
```jsx
<Forms.Currency
    label="Amount"
    name="amount"
    value=""
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/> 
```
{% endraw %}

<br>

### E. DateSelector <a id="DateSelector">
Renders an HTML div element that surrounds an input of type date.

Example:
{% raw %}
```jsx
<Forms.DateSelector
    label="Choose a date"
    name="date"
    value=""
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### F. DateTimeLocal <a id="DateTimeLocal">
Renders an HTML div element that surrounds an input of type datetime-local.

Example:
{% raw %}
```jsx
<Forms.DateTimeLocal
    label="Start date:"
    name="date"
    value=""
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### G. Email <a id="Email">
Renders an HTML div element that surrounds an input of type email.

Example:
{% raw %}
```jsx
<Forms.Email 
    label="Email"
    name="email"
    value={user.email}
    inputAttrs={{className: 'form-control input-sm', placeholder: 'joe@example.com'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### H. FileSelector <a id="FileSelector">
Renders an HTML div element that surrounds an input of type file.

Multiple File Uploads:
Use the $multiple flag to enable multiple file uploads.  Name attribute will be formatted correctly and the multiple attribute will be added to the input element.

Example:
{% raw %}
```jsx
<Forms.FileSelector 
    type="file"
    label="Upload Profile Image (Optional)"
    name="profileImage"
    inputAttrs={{className: 'form-control', accept: 'image/gif image/jpeg image/png'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

Prop names:
- `{string} label` - Sets the label for this input.
- `{string} name` - Sets the value for the name, for, and id attributes for this input.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.
- `{Record<string, string[]>|string[]} [errors=[]]` - The errors object.  Default value is an empty object.
- `{boolean} multiple` - Flag for turning on or off multiple file uploads.

Returns:
- `{HTMLDivElement}` - A surrounding div and the input element of type file.

<br>

### I. Image <a id="Image">
Create a input element of type image.

Example:
{% raw %}
```jsx
import asset from "@chappy/utils/asset";

<Forms.Image 
     id={"submit"} 
     src={asset('public/logo.png', true)}
     width={100}
     height={50}
     inputAttrs={{className: 'pt-4'}}
/>
```
{% endraw %}

Prop names:
- `{string} id` - The id attribute for the image input.
- `{string} src` - The path to the image file.
- `{number} width` - width of the image.
- `{number} height` - height of the image.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.

<br>

### J. ImageBlock <a id="Image">
Create a input element of type image.

Example:
{% raw %}
```jsx
import asset from "@chappy/utils/asset";

<Forms.ImageBlock 
     id={"submit"} 
     src={asset('public/logo.png', true)}
     width={100}
     height={50}
     inputAttrs={{className: 'pt-4'}}
     divAttrs={{className: 'pt-4 text-end'}}
/>
```
{% endraw %}

Prop names:
- `{string} id` - The id attribute for the image input.
- `{string} src` - The path to the image file.
- `{number} width` - width of the image.
- `{number} height` - height of the image.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.

<br>

### K. Interval <a id="Interval">
Assists in the development of input of type range in forms.  It accepts parameters for setting attribute tags in the form section.

Additional props:
- `{number} min` - The minimum value for the interval.
- `{number} max` - The maximum value for the interval.

Example:
{% raw %}
```jsx
<Forms.Interval
    label="Tip amount:"
    name="tick"
    min={0}
    max={45}
    value=""
    divAttrs={{className: 'form-group mb-3 d-flex flex-column'}}
/>
```
{% endraw %}

<br>

### L. Month <a id="Month">
Assists in the development of input of type month in forms.  It accepts parameters for setting attribute tags in the form section.

Example:
{% raw %}
```jsx
<Forms.Month
    label="Select a month:"
    name="month"
    value=""
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### M. Number <a id="Number">
Numeric input (integer or decimal) with optional thousands grouping
and fixed precision.

Config keys (all optional):

| Key | Type(s) | Description |
|:---:|:-------:|-------------|
|  `decimals`    | `int`                    | Decimal places. 0 = integer. Default 0. |
|  `useGrouping` | `bool`                   | Thousands separators on display. Default false. |
|  `min`         | `int|float|null`         | HTML min. Default null (omitted). |
|  `max`         | `int|float|null`         | HTML max. Default null (omitted). |
|  `step`        | `int|float|string|null`  | HTML step. Default derived from decimals. |
|  `locale`      | `string`                 | Intl locale for formatting. Default 'en-US'. |

Stores normalized (raw number, no separators); displays formatted.

Examples:
{% raw %}
```jsx
<Forms.Number 
    label="Integer"
    name="int_demo"
    value={42}
    config={{decimals: 0}}
/>

<Forms.Number
    label="2-decimal, grouped"
    name="price_demo"
    value={1234.5}
    config={{decimals: 2, useGrouping: true}}
/>

<Forms.Number
    label='2-decimal, no grouping'
    name='plain_demo'
    value={1234.5}
    config={{decimals:3, useGrouping: false}}
/>
```
{% endraw %}

Additional prop:
- `{object} config` - Object of optional keys.

<br>

### N. Password <a id="Password">
Assists in the development of input of type text in forms.  It accepts parameters for setting  attribute tags in the form section.

Example:
{% raw %}
```jsx
<Forms.Password 
    label="Password"
    name="password"
    value={user.password}
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### O. Search <a id="Search">
Assists in the development of input of type search in forms.  It accepts parameters for setting  attribute tags in the form section.

Example:
{% raw %}
```jsx
<Forms.Search 
    label="Search"
    name="search"
    value=""
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### P. Tel <a id="Tel">
Renders an HTML div element that surrounds an input of type tel.


Example:
{% raw %}
```jsx
<Forms.Tel 
    label="Cell Phone"
    name="cell"
    value={cellNumber}
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### Q. Text <a id="Text">
Assists in the development of input of type text in forms.  It accepts parameters for setting  attribute tags in the form section.

Example:
{% raw %}
```jsx
<Forms.Text
    label="First Name"
    name="fname"
    value={user.fname}
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```

<br>

### R. TextArea <a id="TextArea">
Assists in the development of textarea in forms.  It accepts parameters for setting  attribute tags in the form section.

Example:
{% raw %}
```jsx
<Forms.TextArea
    label="Description"
    name="description"
    value={user.description || ""}
    inputAttrs={{ placeholder: 'Describe yourself here...' }}
    divAttrs={{ className: 'form-group mb-3' }}
/>
```
{% endraw %}

<br>

### S. Time <a id="Time">
Renders an HTML div element that surrounds an input of type time.

Example:
{% raw %}
```jsx
<Forms.Time
    label="Select a time:"
    name="time"
    value=""
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### T. URLInput <a id="URLInput">
Renders an HTML div element that surrounds an input of type url.

Example:
{% raw %}
```jsx
<Forms.URLInput
    label="Enter a URL:"
    name="url"
    value=""
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

### U. Week <a id="Week">
Renders an HTML div element that surrounds an input of type week.

Example:
{% raw %}
```jsx
<Forms.Week
    label="Select a week:"
    name="week"
    value=""
    inputAttrs={{className: 'form-control input-sm'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

## 14. Output <a id="Output"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#tale-of-contents)</span>
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

## 15. `Radio Buttons` <a id="radio"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
We support radio button groups that allow users to populate the input using information from your database.

Example:
{% raw %}
```jsx
<Forms.RadioGroup 
    name='inactive'
    options={options}
    valueSelected={user.inactive}
    inputAttrs={{className: 'form-check-input'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

Prop names:
- `{string} name` - Sets the value for the name, for, and id attributes for this input.
- `{object} options` - The list of options we will use to populate the radio group.  The default value is an empty object.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.
- `{Record<string,string[]>|string[]} [errors=[]]` - The errors object.  Default value is an empty object.

Returns:
- `{HTMLDivElement}` - A surrounding div and the accompanying nested group of radio inputs.

<br>

**`RadioInput`**
Creates an input element of type radio with an accompanying label element.

Examples:
{% raw %}
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
{% endraw %}

<br>
Prop names:
- `{string} label` - Sets the label for this input.
- `{string} name` - Sets the value for the name attribute for this input.
- `{string} value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  It can be set with values during form validation and forms used for editing records.
- `{bool} checked` - The value for the checked attribute.  If true this attribute will be set as checked="checked".  The default value is false.  It can be set with values during form validation and forms used for editing records.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.

Returns:
- `{JSX.Element}` - The radio input and its label.

<br>

## 16. `RememberMe` <a id="RememberMe"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Renders remember me checkbox.

Prop names:
- `{string} label` - Sets the label for this input.
- `{bool} checked` - The value for the checked attribute.  If true this attribute will be set as checked="checked".  The default value is false.  It can be set with values during form validation and forms used for editing records.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.

Example:
{% raw %}
```jsx
<RememberMe
    label="Remember Me"
    checked={rememberMeChecked}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

<br>

## 17. `RichText` <a id="RichText"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Rich text editor field (TinyMCE) that posts HTML via a hidden input.

Behavior:
- Decodes any HTML entities in `value` before seeding the editor (so `<p>` renders as a paragraph).
- Uses TinyMCE as an uncontrolled editor; current HTML is mirrored into a hidden `<input name={name}>` so your PHP controller receives `$_POST[name]` as HTML.
- UI skin CSS is expected to be imported elsewhere; `skin:false` prevents URL fetches.

Prop names:
- `{string} label` - Sets the label for this input.
- `{string} name` - Sets the value for the name, for, and id attributes for this input.
- `{string} listName` - The list name  and id for the datalist element.
- `{string|number} value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.
- `{Record<string, string[]>|string[]} [errors=[]]` - The errors object.  Default value is an empty object.

Example:
{% raw %}
```jsx
<Forms.RichText
    label="Description"
    name="description"
    value={user.description}
    inputAttrs={{ placeholder: 'Describe yourself here...' }}
    divAttrs={{ className: 'form-group mb-3' }}
/>
```
{% endraw %}

<br>

## 18. `Select` <a id="Select"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Renders a select element with a list of options.

Example:
{% raw %}
```jsx
<Forms.Select 
    label='Status'
    name='inactive'
    options={options}
    valueSelected={user.inactive}
    inputAttrs={{className: 'form-select'}}
    divAttrs={{className: 'form-group mb-3'}}
/>
```
{% endraw %}

Prop names:
- `{string} label` - Sets the label for this input.
- `{string} name` - Sets the value for the name, for, and id attributes for this input.
- `{string} value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `{string} fieldName` - The name of the field in the model to use.
- `{array} options` - The list of options we will use to populate the select option dropdown.  The default value is an empty array.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.
- `{Record<string, string[]>|string[]} [errors=[]]` - The errors object.  Default value is an empty object.


<br>

## 19. `SubmitBlock` <a id="SubmitBlock"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates a div containing an input of type submit.

Prop names:
- `{string} label` - The contents of the button's label.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.

Example:
{% raw %}
```jsx
<Forms.SubmitBlock 
    label="Submit" 
    inputAttrs={{className: 'btn btn-large btn-primary'}}
    divAttrs={{className: 'text-end'}}
/>
```
{% endraw %}

<br>

## 20. `SubmitTag` <a id="SubmitTag"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Create a input element of type submit.

Prop names:
- `{string} label` - The contents of the button's label.
- `{object} inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty object.
- `{object} divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty object.

Example:
{% raw %}
```jsx
<Forms.SubmitTag 
    label="Submit" 
    inputAttrs={{className: 'btn btn-large btn-primary'}}
    divAttrs={{className: 'text-end'}}
/>
```
{% endraw %}