<h1 style="font-size: 50px; text-align: center;">Global Helpers</h1>

## Table of contents
1. [Overview](#overview)
2. [button()](#button)
3. [buttonBlock()](#buttonBlock)
4. [checkboxLabelLeft()](#checkboxLabelLeft)
5. [checkboxLabelRight()](#checkboxLabelRight)
6. [checkboxGroup()](#checkboxGroup)
7. [csrf()](#csrf)
8. [datalists](#datalists)
    * A. [dataListColor()](#dataListColor)
    * B. [dataListDate()](#dataListDate)
    * C. [dataListDateTimeLocal()](#dataListDateTimeLocal)
    * D. [dataListInterval()](#dataListInterval)
    * E. [dataListMonth()](#dataListMonth)
    * F. [dataListText()](#dataListText)
    * G. [dataListTime()](#dataListTime)
    * H. [dataListWeek()](#dataListWeek)
9. [errorBag()](#errorBag)
10. [hidden()](#hidden)
11. [input()](#input)
    * A. [color()](#color)
    * B. [confirm()](#confirm)
    * C. [currency()](#currency)
    * D. [dateSelector()](#dateSelector)
    * E. [dateTimeLocal()](#dateTimeLocal)
    * F. [email()](#email)
    * G. [fileSelector()](#fileSelector)
    * H. [imageButton()](#imageButton)
    * I. [imageBlock()](#imageBlock)
    * J. [interval()](#interval)
    * K. [month()](#month)
    * L. [number()](#number)
    * M. [password()](#password)
    * N. [search()](#search)
    * O. [tel()](#tel)
    * P. [text()](#text)
    * Q. [timeSelector()](#timeSelector)
    * R. [urlInput()](#urlInput)
    * S. [week()](#week)
output
radio
radioGroup
rememberMe
select
submitBlock
submit
textarea
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Chappy.php has a rich library of form globals.  Many are wrappers of existing functions in the `FormHelper` class.  17 of them leverage the original `FormHelper::InputBlock` function.  There are also specialized functions for radio buttons, checkboxes, option select, currency, and numbers.

<br>

## 2. `button()` <a id="button"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This function creates a button with no surrounding HTML div element. It supports the ability to set attributes such as classes and event handlers. If you want a div to surround a button along with any other attributes we recommend that you use the buttonBlock function. Note the example function call shown below:

```php
button(
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

## 3. `buttonBlock()` <a id="buttonBlock"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The buttonBlock function is a wrapper for the button function that adds a div around the button element. An example function call is shown below.

```php
buttonBlock(
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

## 4. `checkboxLabelLeft()` <a id="checkboxLabelLeft"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates a checkbox where the label is on the left side. It generates a div element that surrounds a label and input of type checkbox. This is ideal for situations where labels can be of varying lengths. An example function call is shown below.

```php
checkboxLabelLeft(
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

## 5. `checkboxLabelRight()` <a id="checkboxLabelRight"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates a checkbox where the label is on the right side. It generates a div element that surrounds a label and input of type checkbox. An example function call from the login view is shown below.

```php
checkboxLabelRight(
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

## 6. `checkboxGroup()` <a id="checkboxGroup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Renders a group of checkboxes sharing one name (submitted as name[]), one wrapping div, and ONE error span. Label-right per box.

Example:

```php
<?= checkboxGroup(
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

## 7. `csrf()` <a id="csrf"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Inserts csrf token into form.

Returns: 
- string The hidden input of type hidden with the generated token set as the value.

<br>

## 8. `datalists()` <a id="datalists"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This section contains a listing of input functions paired with a datalist for suggestions.

Parameters common among all of these functions are:
- `string $label` - Sets the label for this input.
- `string $name` - Sets the value for the name, for, and id attributes for this input.
- `string $listName` - The list name  and id for the datalist element.
- `mixed $value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `array $options` - A list of suggestions.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.
- `array $errors` - The errors array.  Default value is an empty array.

<br>

### A. `dataListColor()` <a id="dataListColor">
Renders an HTML div element that surrounds an input of type color with an accompanying datalist of suggestions.

Signature:
```php
function dataListColor(
    string $label, 
    string $name, 
    string $listName,
    mixed $value = '', 
    array $options = [],
    array $inputAttrs = [], 
    array $divAttrs = [],
    array $errors=[]
): string
```

Returns:
- `string` - A surrounding div and the input element of type color.

<br>

### B. `dataListDate()` <a id="dataListDate">
Renders an HTML div element that surrounds an input of type date with an accompanying datalist of suggestions.

Signature:
```php
function dataListDate(
    string $label, 
    string $name, 
    string $listName,
    mixed $value = '', 
    array $options = [],
    array $inputAttrs = [], 
    array $divAttrs = [],
    array $errors=[]
): string
```

Returns:
- `string` - A surrounding div and the input element of type date.

<br>

### C. `dataListDateTimeLocal()` <a id="dataListDateTimeLocal">
Renders an HTML div element that surrounds an input of type datetime-local with an accompanying datalist of suggestions.

Signature:
```php
function dataListDateTimeLocal(
    string $label, 
    string $name, 
    string $listName,
    mixed $value = '', 
    array $options = [],
    array $inputAttrs = [], 
    array $divAttrs = [],
    array $errors=[]
): string
```

Returns:
- `string` - A surrounding div and the input element of type datetime-local.

<br>

### D. `dataListInterval()` <a id="dataListInterval">
Renders an HTML div element that surrounds an input of type range with an accompanying datalist of suggestions.

Signature:
```php
function dataListInterval(
    string $label, 
    string $name, 
    string $listName,
    int|float $min,     // The minimum value for the interval.
    int|float $max,     // The maximum value for the interval.
    mixed $value = '', 
    array $options = [],
    array $inputAttrs = [], 
    array $divAttrs = [],
    array $errors=[]
): string
```

<br>

### E. `dataListMonth()` <a id="dataListMonth">
Renders an HTML div element that surrounds an input of type month with an accompanying datalist of suggestions.

Signature:
```php
function dataListMonth(
    string $label, 
    string $name, 
    string $listName,
    mixed $value = '', 
    array $options = [],
    array $inputAttrs = [], 
    array $divAttrs = [],
    array $errors=[]
): string
```

Returns:
- `string` - A surrounding div and the input element of type month.

<br>

### F. `dataListText()` <a id="dataListText">
Renders an HTML div element that surrounds an input of type text with an accompanying datalist of suggestions.

Signature:
```php
function dataListText(
    string $label, 
    string $name, 
    string $listName,
    mixed $value = '', 
    array $options = [],
    array $inputAttrs = [], 
    array $divAttrs = [],
    array $errors=[]
): string
```

Returns:
- `string` - A surrounding div and the input element of type text.

<br>

### G. `dataListTime()` <a id="dataListTime">
Renders an HTML div element that surrounds an input of type time with an accompanying datalist of suggestions.

Signature:
```php
function dataListTime(
    string $label, 
    string $name, 
    string $listName,
    mixed $value = '', 
    array $options = [],
    array $inputAttrs = [], 
    array $divAttrs = [],
    array $errors=[]
): string
```

Returns:
- `string` - A surrounding div and the input element of type time.

<br>

### H. `dataListWeek()` <a id="dataListWeek">
Renders an HTML div element that surrounds an input of type week with an accompanying datalist of suggestions.

Signature:
```php
function dataListWeek(
    string $label, 
    string $name, 
    string $listName,
    mixed $value = '', 
    array $options = [],
    array $inputAttrs = [], 
    array $divAttrs = [],
    array $errors=[]
): string
```

Returns:
- `string` - A surrounding div and the input element of type week.

<br>

## 9. `errorBag()` <a id="errorBag"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns list of errors.  A wrapper for the `FormHelper::displayErrors()` function.

Parameter:
- `array|ArraySet $errors` - A list of errors and their description that is generated during server side form validation.

Returns:
- `string` - A string representation of a div element containing an input of type checkbox.

<br>

## 10. `hidden()` <a id="hidden"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates a hidden element. An example function call is shown below in figure 7:

Example:
```php
hidden("example_name", "example_value");
```

This function accepts 2 arguments as described below:
1. $name sets the value for the name, for, and id attributes.
2. $value The value for the value attribute.

<br>

## 11. `input()` <a id="input"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This section contains a listing of input functions that are wrappers for the HTML input element.

Parameters common among all of these functions are:
- `string $label` - Sets the label for this input.
- `string $name` - Sets the value for the name, for, and id attributes for this input.
- `string $listName` - The list name  and id for the datalist element.
- `mixed $value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `array $options` - A list of suggestions.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.
- `array $errors` - The errors array.  Default value is an empty array.

<br>

### A. `color()` <a id="color">
Renders an HTML div element that surrounds an input of type color.

Signature:
```php
function color(
    string $label,
    string $name,
    mixed $value = '',
    array $inputAttrs = [],
    array $divAttrs = [],
    array $errors = []
): string
```

Returns:
- `string` - A surrounding div and the input element of type color.

<br>

### B. `confirm()` <a id="confirm">
Renders an HTML div element that surrounds an input of type password confirm.  The built-in contract assumes that "confirm" is the name of the field.

Example:
```php
<?= confirm(
     "Confirm Password", 
     $this->user->confirm, 
     ['class' => 'form-control input-sm'], 
     ['class' => 'form-group mb-3']) 
?>
```

Parameters:
- `string $label` - Sets the label for this input.
- `mixed $value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.
- `array $errors` - The errors array.  Default value is an empty array.

Returns:
- `string` - A surrounding div and the input element of type password.

<br>

### C. `currency()` <a id="currency">
Renders an HTML div element that surrounds an input of type currency.

Example:
```php
<?= currency(
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

### D. `dateSelector()` <a id="dateSelector">
Renders an HTML div element that surrounds an input of type date.

Signature:
```php
function dateSelector(
    string $label,
    string $name,
    mixed $value = '',
    array $inputAttrs = [],
    array $divAttrs = [],
    array $errors = []
): string
```

Returns:
- `string` - A surrounding div and the input element of type date.

<br>

### E. `dateTimeLocal()` <a id="dateTimeLocal">
Renders an HTML div element that surrounds an input of type datetime-Local.

Signature:
```php
function dateTimeLocal(
    string $label,
    string $name,
    mixed $value = '',
    array $inputAttrs = [],
    array $divAttrs = [],
    array $errors = []
): string
```

Returns:
- `string` - A surrounding div and the input element of type datetime-local.

<br>

### F. `email()` <a id="email">
Renders an HTML div element that surrounds an input of type email.

Signature:
```php
function email(
    string $label,
    string $name,
    mixed $value = '',
    array $inputAttrs = [],
    array $divAttrs = [],
    array $errors = []
): string
```

Returns:
- `string` - A surrounding div and the input element of type email.

<br>

### G. `fileSelector()` <a id="fileSelector">
Renders an HTML div element that surrounds an input of type file.

**Multiple File Uploads:**

Use the $multiple flag to enable multiple file uploads.  Name attribute 
will be formatted correctly and the multiple attribute will be added to 
the input element.

Example:

```php
<?= fileSelector(
     "Upload Profile Image (Optional)", 
     'profileImage', 
     ['class' => 'form-control', 'accept' => 'image/gif image/jpeg image/png'], 
     ['class' => 'form-group mb-3']) 
?>
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

### H. `imageButton()` <a id="imageButton">
Create a input element of type image.

Example:

```php
<?= imageButton(
     'submit', 
     asset('public/logo.png', true), 
     100, 
     50, 
     ['class' => 'mt-5 pt-4']
?>
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

### I. `imageBlock()` <a id="imageBlock">
Renders an HTML div element that surrounds an input of type image.

Example:
```php
<?= image(
     'submit', 
     asset('public/logo.png', true), 
     100, 
     50, 
     ['class' => 'mt-5 pt-4'], 
     ['class' => 'text-end'])
?>
```

Parameters:
- `string $id` - The id attribute for the image input.
- `string $src` - The path to the image file.
- `int $width` - The width of the image.
- `int $height` - The hight of the image.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- ``array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.

Returns:
- `string` - An input element of type image.

<br>

### J. `interval()` <a id="interval">
Renders an HTML div element that surrounds an input of type interval.

Signature:
```php
function interval(
    string $label,
    string $name,
    int|float $min,     // The minimum value for the interval.
    int|float $max,     // The maximum value for the interval.
    mixed $value = '',
    array $inputAttrs = [],
    array $divAttrs = [],
    array $errors = []
): string
```

<br>

### K. `month()` <a id="month">
Renders an HTML div element that surrounds an input of type month.

Signature:
```php
function month(
    string $label,
    string $name,
    mixed $value = '',
    array $inputAttrs = [],
    array $divAttrs = [],
    array $errors = []
): string
```

Returns:
- `string` - A surrounding div and the input element of type month.

<br>

### L. `number()` <a id="number"></a>
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
<?= number('Integer', 'int_demo', 42, ['decimals' => 0]); ?>

<?= number('2-decimal, grouped', 'price_demo', 1234.5,
      ['decimals' => 2, 'useGrouping' => true]); ?>

<?= number('2-decimal, no grouping', 'plain_demo', 1234.5,
      ['decimals' => 2, 'useGrouping' => false]); ?>

<?= number('3-decimal precision', 'precise_demo', 3.14159,
      ['decimals' => 3, 'useGrouping' => true]); ?>

<?= number('With min/max', 'bounded_demo', 50,
      ['decimals' => 0, 'min' => 0, 'max' => 100]); ?>

<?= number('Empty (create mode)', 'empty_demo', '',
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

### M. `password()` <a id="password">
Renders an HTML div element that surrounds an input of type password.

Example:
```php
<?= password(
    'Password', 
    'password', 
    $this->login->password,
    ['class' => 'form-control input-sm'], 
    ['class' => 'form-group mb-3']) 
?>
```

Parameters:
- `string $label` - Sets the label for this input.
- `mixed $value` - The value we want to set.  We can use this to set the value of the value attribute during form validation.  Default value is the empty string.  It can be set with values during form validation and forms used for editing records.
- `array $inputAttrs` - The values used to set the class and other attributes of the input string.  The default value is an empty array.
- `array $divAttrs` - The values used to set the class and other attributes of the surrounding div.  The default value is an empty array.
- `array $errors` - The errors array.  Default value is an empty array.

Returns:
- `string` - A surrounding div and the input element of type password.

<br>

### N. `search()` <a id="search">
Renders an HTML div element that surrounds an input of type search.

Signature:
```php
function search(
    string $label,
    string $name,
    mixed $value = '',
    array $inputAttrs = [],
    array $divAttrs = [],
    array $errors = []
): string
```

Returns:
- `string` - A surrounding div and the input element of type search.

<br>

### O. `tel()` <a id="tel">
Renders an HTML div element that surrounds an input of type tel.

Signature:
```php
function search(
    string $label,
    string $name,
    mixed $value = '',
    array $inputAttrs = [],
    array $divAttrs = [],
    array $errors = []
): string
```

Returns:
- `string` - A surrounding div and the input element of type tel.

<br>

### P. `text()` <a id="text">
Renders an HTML div element that surrounds an input of type text.

Signature:
```php
function text(
    string $label,
    string $name,
    mixed $value = '',
    array $inputAttrs = [],
    array $divAttrs = [],
    array $errors = []
): string
```

Returns:
- `string` - A surrounding div and the input element of type text.

<br>

### Q. `timeSelector()` <a id="timeSelector">
Renders an HTML div element that surrounds an input of type time.

Signature:
```php
function timeSelector(
    string $label,
    string $name,
    mixed $value = '',
    array $inputAttrs = [],
    array $divAttrs = [],
    array $errors = []
): string
```

Returns:
- `string` - A surrounding div and the input element of type time.

<br>

### R. `urlInput()` <a id="urlInput">
Renders an HTML div element that surrounds an input of type url.

Signature:
```php
function urlInput(
    string $label,
    string $name,
    mixed $value = '',
    array $inputAttrs = [],
    array $divAttrs = [],
    array $errors = []
): string
```

Returns:
- `string` - A surrounding div and the input element of type url.

<br>

### R. `week()` <a id="week">
Renders an HTML div element that surrounds an input of type week.

Signature:
```php
function week(
    string $label,
    string $name,
    mixed $value = '',
    array $inputAttrs = [],
    array $divAttrs = [],
    array $errors = []
): string
```

Returns:
- `string` - A surrounding div and the input element of type week.

<br>