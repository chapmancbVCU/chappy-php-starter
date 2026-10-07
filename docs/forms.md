<h1 style="font-size: 50px; text-align: center;">Form Helper Functions</h1>

## Table of contents
1. [Overview](#overview)
2. [appendErrorClass()](#append-error-class)
3. [button()](#button)
4. [buttonBlock()](#buttonblock)
5. [checkboxBlockLabelLeft()](#checkboxBlockLabelLeft)
6. [checkboxBlockLabelRight()](#checkboxblocklabelright)
7. [checkboxGroup()](#checkbox-group)
8. [checkboxInput()](#checkbox-input)
9. [checkToken()](#check-token)
10. [csrfInput()](#csrf-input)
11. [displayErrors()](#displayerrors)
12. [errorMsg()](#error-msg)
13. [generateToken()](#generate-token)
14. [hidden()](#hidden)
15. [inputBlock()](#inputblock)
 * A. [currencyBlock()](#currency-block)
 * B. [emailBlock()](#emailblock)
 * C. [fileBlock()](#file-block)
 * D. [image()](#image)
 * E. [imageBlock()](#image-block)
 * F. [number](#number)
 * G. [telBlock()](#tel-block)
 * H. [textAreaBlock()](#textarea-block)
16. [dataListBlock()](#datalist-block)
17. [selectBlock()](#select)
18. [output()](#output)
19. [posted_values()](#posted-values)
20. [Radio Buttons](#radioinput)
21. [sanitize()](#sanitize)
22. [stringifyAttrs()](#stringify-attrs)
23. [submitBlock()](#submitblock)
24. [submitTag()](#submittag)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The Rapid Forms feature of this Model View Controller (MVC) Framework allows the user to quickly create and style forms. This guide thoroughly describes the ability to create these HTML form elements along with a description and examples. All form inputs will automatically be sanitized and validation checks will be performed.  If you would like support for additional features please create an issue [here](https://github.com/chapmancbVCU/chappy-php-framework/issues).


<br>

## 2. `appendErrorClass()` <a id="append-error-class"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)
Adds name of error classes to div associated with a form field.

Parameters:
- `array $attrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $errors` - The errors array.
- `string $name` - The name of the field associated with this error.
- `string $class` - Name of the class used to identify errors for a form field.

Returns:
- `array` - Div attributes with error classes added.

<br>

## 3. `button()` <a id="button"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This function creates a button with no surrounding HTML div element. It supports the ability to set attributes such as classes and event handlers. If you want a div to surround a button along with any other attributes we recommend that you use the buttonBlock function. Note the example function call shown below:

```php
FormHelper::button(
  "Click Me!", 
  ['class' => 'btn btn-large btn-primary', 'onClick' => 'alert(\'Hello World!\')']
);
```

Parameters: 
- `string $buttonText` - The contents of the button's label.
- `array $inputAttrs` - This parameter is used to set values for attributes such as classes for styling, front-side validation, and event handlers. Make sure when performing an event handler function call that contains strings as arguments to escape any quotes. The default value is an empty array.

Returns:
- An HTML button element with its label set and any other optional attributes set.

<br>

## 4. `buttonBlock()` <a id="buttonblock"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The buttonBlock function is a wrapper for the button function that adds a div around the button element. An example function call is shown below.

```php
FormHelper::buttonBlock(
  "Click Me!", 
  ['class' => 'btn btn-large btn-primary', 'onClick' => 'alert(\'Hello World!\')'], 
  ['class' => 'form-group']
);
```

Parameters:
- `string $buttonText` - The contents of the button's label.
- `array $inputAttrs` - This parameter is used to set values for attributes such as classes for styling, front-side validation, and event handlers. Make sure when performing an event handler function call that contains strings as arguments to escape any quotes. The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.

Returns:
- `string` - An HTML div surrounding a button element with its label set and any other optional attributes set.

<br>

## 5. `checkboxBlockLabelLeft()` <a id="checkboxBlockLabelLeft"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates a checkbox where the label is on the left side. It generates a div element that surrounds a label and input of type checkbox. This is ideal for situations where labels can be of varying lengths. An example function call is shown below.

```php
FormHelper::checkboxBlockLabelLeft(
  'Remember Me', 
  'remember_me', 
  'on', 
  $this->login->getRememberMeChecked(), 
  [], 
  ['class' => 'form-group'], $this->displayErrors
);
```

Parameters:
- `string $label` - Sets the label for this input.
- `string $name` - Sets the value for the name, for, and id attributes for this input.
- `string $value` - The value we want to set.  We can use this to set  the value of the value attribute during form validation.  Default value  is the empty string.  It can be set with values during form validation and forms used for editing records.
- `bool $checked` - The value for the checked attribute.  If true  this attribute will be set as checked="checked".  The default value is false.  It can be set with values during form validation and forms used for editing records.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.
- `array $errors` - The errors array.  Default value is an empty array.

Returns:
- `string` - A surrounding div and the input element of type checkbox.

<br>

## 6. `checkboxBlockLabelRight()` <a id="checkboxblocklabelright"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates a checkbox where the label is on the right side. It generates a div element that surrounds a label and input of type checkbox. An example function call from the login view is shown below.

```php
FormHelper::checkboxBlockLabelRight(
  'Remember Me', 
  'remember_me', 
  'on', 
  $this->login->getRememberMeChecked(), 
  [], 
  ['class' => 'form-group mr-1'], $this->displayErrors
);
```
Parameters:
- `string $label` - Sets the label for this input.
- `string $name` - Sets the value for the name, for, and id attributes for this input.
- `string $value` - The value we want to set.  We can use this to set  the value of the value attribute during form validation.  Default value  is the empty string.  It can be set with values during form validation and forms used for editing records.
- `bool $checked` - The value for the checked attribute.  If true  this attribute will be set as checked="checked".  The default value is false.  It can be set with values during form validation and forms used for editing records.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.
- `array $errors` - The errors array.  Default value is an empty array.

Returns:
- `string` - A surrounding div and the input element of type checkbox.

<br>

## 7. `checkboxGroup()` <a id="checkbox-group"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Renders a group of checkboxes sharing one name (submitted as name[]), one wrapping div, and ONE error span. Label-right per box.

Example:

```php
<?= FormHelper::checkboxGroup(
  'acls',
  $this->acls,              // [value => label] map of all ACLs
  $this->user->getAcls(),   // the set of ACLs this user currently has
  [],
  ['class' => 'form-check'],
  $this->displayErrors
); ?>
```

Parameters:
- `string $name` - Group name WITHOUT '[]' (added internally), e.g. 'acls'.
- `array $options` - [value => label] map of choices. 
- `array $selectedValues` - Values that should render checked (the current set).
- `array $inputAttrs` - Passthrough attrs applied to every box (error-classed once here).
- `array $divAttrs` - Attrs for the group's wrapping div.
- `array $errors` - Errors array; one invalid-feedback span for the whole group.

Returns:
- `string` - The checkbox group.

<br>

## 8. `checkboxInput()` <a id="checkbox-input"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Renders a single checkbox input with its label — the bare item only, no wrapping div and no error span (the caller owns the envelope). Label sits to the RIGHT of the box by default; pass `$labelRight = false` to place it on the left.

`$inputAttrs` is expected already error-classed by the caller (mirrors radioInput).

Parameters:
- `string $label` - Visible label text.
- `string $name` - Field name. Keep the '`[]`' suffix for group members (e.g. '`genres[]`') so they submit as an array.
- `string $value` -  Submitted value; also used to build a unique id.
- `bool $checked` - Whether this box renders checked.
- `array $inputAttrs` - Passthrough HTML attributes (already error-classed).

Returns:
- `bool $labelRight` - Label on the right of the box (default true).

<br>

## 9. `checkToken()` <a id="check-token"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Checks if the csrf token exists.  This is used to verify that there has been no tampering of a form's csrf token.

Parameter:
- `string $token` - token string we will test whether or not it exists.

Returns:
- `bool` - The result of the AND operation on whether or not a token exists with a session and if the session's token is equal to the value of the $token parameter.

<br>

## 10. `csrfInput()` <a id="csrf-input"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
A hidden input to represent the csrf token in a web form.

Returns: 
- string The hidden input of type hidden with the generated token set as the value.

<br>

## 11. `displayErrors()` <a id="displayerrors"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The purpose of this function is to display errors related to validation.  Many frameworks calls this an error bag.


Parameters:
- `array|ArraySet $errors` - A list of errors and their description that is generated during server side form validation.

Returns:
- `string` - The error bag.

<br>

## 12. `errorMsg()` <a id="error-msg"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Renders an error message for a particular form field.

Parameters:
- `array $errors` - The error array.
- `string $name` - Used to search errors array for key/form field.

Returns:
- `string` - The error message for a particular field.

<br>


## 13. `generateToken()` <a id="generate-token"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Creates a randomly generated csrf token. 

Returns:
- string - The randomly generated token.

<br>

## 14. `hidden()` <a id="hidden"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates a hidden element. An example function call is shown below in figure 7:

Example:
```php
FormHelper::hidden("example_name", "example_value");
```

This function accepts 2 arguments as described below:
1. $name sets the value for the name, for, and id attributes.
2. $value The value for the value attribute.

<br>

## 15. `inputBlock()` <a id="inputblock"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
A generic block for the input element.  Most input types in the forms global library depend on this function.

Example:

```php
FormHelper::inputBlock(
  'text', 
  'Example', 
  'example_name', 
  example_value, 
  ['class' => 'form-control'], 
  ['class' => 'form-group'], 
  $this->displayErrors
);
```

Parameters:
- `string` $type - The input type we want to generate.
- `string $label` - Sets the label for this input.
- `string $name` - Sets the value for the name, for, and id attributes for this input.
- `mixed $value` - The value we want to set.  We can use this to set  the value of the value attribute during form validation.  Default value  is the empty string.  It can be set with values during form validation and forms used for editing records.
- `bool $checked` - The value for the checked attribute.  If true  this attribute will be set as checked="checked".  The default value is false.  It can be set with values during form validation and forms used for editing records.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.
- `array $errors` - The errors array.  Default value is an empty array.

Returns:
- `string` - A surrounding div and the input element.

<br>

### A. `currencyBlock()` <a id="currency-block">
Renders an HTML div element that surrounds an input of type currency.

Example:

```php
<?= FormHelper::currencyBlock(
    label: 'Amount',
    name: "amount",
    inputAttrs: ['class' => 'form-control input-sm'],
    divAttrs: ['class' => 'form-group mb-3']
) ?>
```

Parameters:
- `string $label` - Sets the label for this input.
- `string $name` - Sets the value for the name, for, and id attributes for this input.
- `string $intlNumberFormat` - The international number format.
- `string $currency` - The 3 digit currency name.
- `mixed $value` - The value we want to set.  We can use this to set  the value of the value attribute during form validation.  Default value  is the empty string.  It can be set with values during form validation and forms used for editing records.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.
- `array $errors` - The errors array.  Default value is an empty array.

Returns:
- `string` - A surrounding div and the input element of type text for currency.

<br>

### B. `emailBlock()` <a id="emailblock">
Use this function to create styled E-mail form inputs. 

Example:

```php
FormHelper::emailBlock(
  'Email', 
  'email', 
  $this->contact->email, 
  ['class' => 'form-control'], 
  ['class' => 'form-group col-md-6'], 
  $this->displayErrors
);
```

Parameters:
- `string $label` - Sets the label for this input.
- `string $name` - Sets the value for the name, for, and id attributes for this input.
- `string $value` - The value we want to set.  We can use this to set  the value of the value attribute during form validation.  Default value  is the empty string.  It can be set with values during form validation and forms used for editing records.
- `bool $checked` - The value for the checked attribute.  If true  this attribute will be set as checked="checked".  The default value is false.  It can be set with values during form validation and forms used for editing records.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.
- `array $errors` - The errors array.  Default value is an empty array.

Returns:
- `string` - A surrounding div and the input element of type email.

<br>

### C. `fileBlock()` <a id="file-block">
Renders an HTML div element that surrounds an input of type file.

**Multiple File Uploads:**

Use the $multiple flag to enable multiple file uploads.  Name attribute will be formatted correctly and the multiple attribute will be added to the input element.

Example:
```php
<?= FormHelper::fileBlock(
  "Upload Profile Image (Optional)", 
  'profileImage', 
  ['class' => 'form-control', 'accept' => 'image/gif image/jpeg image/png'], 
  ['class' => 'form-group mb-3']
) ?>
```
Parameters:
- `string $label` - Sets the label for this input.
- `string $name` - Sets the value for the name, for, and id attributes for this input.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.
- `bool $multiple` - Flag for turning on or off multiple file uploads.
- `array $errors` - The errors array.  Default value is an empty array.

Returns:
- `string` - A surrounding div and the input element of type file.

<br>

### D. `image()` <a id="image">
Create a input element of type image.

Example:
```php
<?= FormHelper::image(
     'submit', 
     asset('public/logo.png', true), 
     100, 
     50, 
     ['class' => 'mt-5 pt-4']
) ?>
```

Parameters:
- `string $id` - The id attribute for the image input.
- `string $src` - The path to the image file.
- `int $width` - The width of the image.
- `int $height` - The hight of the image.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.

Returns:
- `string` - An input element of type image.

<br>

### E. `imageBlock()` <a id="image-block">
Create a input element of type image.

Example:
```php
<?= FormHelper::imageBlock(
     'submit', 
     asset('public/logo.png', true), 
     100, 
     50, 
     ['class' => 'mt-5 pt-4'],
     ['class' => 'text-end']
) ?>
```

Parameters:
- `string $id` - The id attribute for the image input.
- `string $src` - The path to the image file.
- `int $width` - The width of the image.
- `int $height` - The hight of the image.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.

Returns:
- `string` - An input element of type image.

<br>

### F. `number()` <a id="number"></a>
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

```php
<?= FormHelper::number('Integer', 'int_demo', 42, ['decimals' => 0]); ?>

<?= FormHelper::number('2-decimal, grouped', 'price_demo', 1234.5,
      ['decimals' => 2, 'useGrouping' => true]); ?>

<?= FormHelper::number('2-decimal, no grouping', 'plain_demo', 1234.5,
      ['decimals' => 2, 'useGrouping' => false]); ?>

<?= FormHelper::number('3-decimal precision', 'precise_demo', 3.14159,
      ['decimals' => 3, 'useGrouping' => true]); ?>

<?= FormHelper::number('With min/max', 'bounded_demo', 50,
      ['decimals' => 0, 'min' => 0, 'max' => 100]); ?>

<?= FormHelper::number('Empty (create mode)', 'empty_demo', '',
    ['decimals' => 2, 'useGrouping' => true]); ?>
```

Parameters:
- `string $label` - Sets the label for this input.
- `string $name` - Sets the value for the name, for, and id attributes  for this input.
- `mixed $value` - The value we want to set.  We can use this to set  the value of the value attribute during form validation.  Default value  is the empty string.  It can be set with values during form validation  and forms used for editing records.
- `array $config` - Array of optional keys.
- `array $inputAttrs` - The values used to set the class and other  attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other  attributes of the surrounding div.  The default value is an empty array.
- `array $errors` - The errors array.  Default value is an empty array. 

Returns:
- `string` - A surrounding div and a formatted number input field.

<br>

### G. `telBlock()` <a id="tel-block"></a>
Renders an HTML div element that surrounds an input of type tel. The user is able to enter cell, home, and work as phone types. Certain options can be set using the args parameter.

This function will be deprecated in version 5.0.0 and the global helper will be used instead.

Example:

```php
<?= FormHelper::telBlock(
     'Home phone', 
     'phone', 
     $this->user->phone, 
     ['class' => 'form-control input-sm'], 
     ['class' => 'form-group mb-3']) 
?>
```

Parameters:
- `string $label` - Sets the label for this input.
- `string $name` - Sets the value for the name, for, and id attributes for this input.
- `mixed $value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value  is the empty string.  It can be set with values during form validation  and forms used for editing records.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other  attributes of the surrounding div.  The default value is an empty array.
- `array $errors` - The errors array.  Default value is an empty array.

Returns:
- string - The HTML div element surrounding an input of type tel with configuration and values set based on parameters entered during function call.

<br>

### H. `textAreaBlock()` <a id="textarea-block">
Assists in the development of textarea in forms. It accepts parameters for setting attribute tags in the form section.  An example function call is shown below:

```php
<!-- Add this to the head section -->
<?php $this->start('head') ?>
<?= loadTinyMCE() ?>
<?php $this->end() ?>

<!-- The function call -->
<?= FormHelper::textareaBlock("Description", 
    'description', 
    $this->user->description, 
    ['class' => 'form-control input-sm', 'placeholder' => 'Describe yourself here...'], 
    ['class' => 'form-group mb-3']); 
?>

<!-- Wait until content is loaded before we initialize script -->
<?= initTinyMCE('description') ?>
```

Parameters:
- `string $label`  Sets the label for this input.
- `string $name` - Sets the value for the name, for, and id attributes  for this input.
- `string|null $value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.
- `array $errors` - The errors array.  Default value is an empty array.

Returns:
- `string` - A surrounding div and the textarea element.

<br>

## 16. `datalistBlock()` <a id="datalist-block"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Assists in the development of forms input blocks with datalist element in forms.  It accepts parameters for setting attribute tags in the form section.

Parameters:
- `string $type` - The input type we want to generate.
- `string $label` - Sets the label for this input.
- `string $name` - Sets the value for the name, for, and id attributes for this input.
- `string $listName` - The list name and id for the datalist element.
- `mixed $value` - The value we want to set.  We can use this to set  the value of the value attribute during form validation.  Default value  is the empty string.  It can be set with values during form validation and forms used for editing records.
- `array $options` - A list of suggestions.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.
- `array $errors` - The errors array.  Default value is an empty array.

Returns:
- `string` - A surrounding div and the input element.

<br>

## 17. `selectBlock()` <a id="select"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
HTML `<select>` elements are supported with the calls to the `FormHelper::selectBlock` function.

Example:

```php
<?= FormHelper::selectBlock(
    'Account Status',                 // label
    'inactive',                       // name — matches the schema column
    $this->user->inactive,            // current value: 0 or 1
    [0 => 'Active', 1 => 'Inactive'], // [value => label] map
    ['class' => 'form-select'],       // inputAttrs
    ['class' => 'form-group mb-3'],   // divAttrs
    $this->displayErrors              // errors
); ?>
```
Parameters:
- `string $label` - Sets the label for this input.
- `string $name` - Sets the value for the name, for, and id attributes for this input.
- `string $value` - The value we want to set as selected.
- `array $options` - The list of options we will use to populate the select option dropdown.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.
- `array $errors` - The errors array.  Default value is an empty array.

Returns:
- `string` - A surrounding div and option select element.

<br>

## 18. `output()` <a id="output"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates an HTML output element. The output element is a container that can inject the results of a calculator or the outcome of a user action. An example function call is shown below in figure 9:

Example:
```php
FormHelper::output("my_name", "for_value")
```

Parameters:
- `string` - $name Sets the value for the name attributes for this input.
- `string` - $for Sets the value for the for attribute.

Returns:
- `string` - The HTML output element.

<br>

## 19. `posted_values()` <a id="posted-values"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Sanitizes values from $_POST input arrays to prevent malicious script injection.
```php
$post = FormHelper::posted_values($_POST);
```

<br>

## 20. `Radio Buttons` <a id="radioinput"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
We support radio button groups that allow users to populate the input using information from your database.

Example:

```php
<?= FormHelper::radioGroup(
    'inactive',                       // name
    [0 => 'Active', 1 => 'Inactive'], // [value => label] map
    $this->user->inactive,            // selected value: 0 or 1
    ['class' => 'form-check-input'],  // inputAttrs (applied to every radio)
    ['class' => 'form-group mb-3'],   // divAttrs
    $this->displayErrors              // errors
); ?>
```

Parameters:
- `string $name`-  Sets the value for the name attribute for this input.
- `array $options`-  The list of options we will use to populate the radio group.
- `string|int|bool $selectedValue`-  The selected value.
- `array $inputAttrs`-  The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs`-  The values used to set the class and other  attributes of the surrounding div.  The default value is an empty array.
- `array $errors`-  The errors array.  Default value is an empty array.

Returns:
- `string` - A surrounding div and option select element.

<br>

**`radioInput()`**
The `radioGroup()` function calls `radioInput` for each radio button to be generated.

Parameters:
- `string $label` - Sets the label for this input.
- `string $name` - Sets the value for the name attribute for this input.
- `string $value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  It can be set with values during form validation and forms used for editing records.
- `bool $checked` - The value for the checked attribute.  If true this attribute will be set as checked="checked".  The default value is false.  It can be set with values during form validation and forms used for editing records.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.

Returns:
- `string` - The HTML input element of type radio.

The example code below demonstrates how a radio button are used.
```php
FormHelper::radioInput('HTML', 'html', 'fav_language', "HTML", $check1, ['class' => 'form-group mr-1']); 
FormHelper::radioInput('CSS', 'css', 'fav_language', "CSS", $check2, ['class' => 'form-group mr-1']);
```

<br>

## 21. `sanitize()` <a id="sanitize"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Sanitizes potentially harmful string of characters.

Parameter:
- `string|array` $dirty - The potentially dirty string.

Return:
- `string` - The sanitized version of the dirty string.

<br>

## 22. `stringifyAttrs()` <a id="stringify-attrs"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `stringifyAttrs` function is a utility method used internally by other `FormHelper` functions. Its purpose is to convert an associative array of HTML attributes into a properly formatted string that can be directly inserted into an HTML tag. This makes it easier to dynamically construct form fields with customizable attributes such as `class`, `id`, `placeholder`, and event listeners.

While this function is typically used by the framework internally, understanding it can help when extending or customizing form rendering.

**Example Usage**
```php
$attrs = [
  'class' => 'form-control',
  'placeholder' => 'Enter your name',
  'onfocus' => "this.select()"
];

echo FormHelper::stringifyAttrs($attrs);
```

**Output**
```php
 class="form-control" placeholder="Enter your name" onfocus="this.select()"
```

As shown, the function iterates over the array and produces a valid string of HTML attributes with proper spacing. This string is then injected into tag constructors like `<input>`, `<select>`, `<textarea`>, or `<button>` elements.

**When You Might Use This**
-Creating your own custom form rendering methods.
- Passing additional attributes dynamically through controller logic.
- Building reusable components that take optional HTML attributes

<br>

## 23. `submitBlock()` <a id="submitblock"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates a div containing an input of type submit.

Example:
```php
FormHelper::submitBlock(
    "Save", 
    ['class'=>'btn btn-primary'], ['class'=>'text-end']
);
```
Parameters:
- `string $buttonText` - Sets the value of the text describing the button.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.

Returns:
- `string` -  A surrounding div and the input element of type submit.
<br>

## 24. `submitTag()` <a id="submittag"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Create a input element of type submit.

Example:
```php
FormHelper::submitTag(
    "Save", 
    ['class'=>'btn btn-primary']
);
```

Parameters:
- `string $buttonText` - Sets the value of the text describing the button.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.

Returns:
- `string` -  A surrounding div and the input element of type submit.