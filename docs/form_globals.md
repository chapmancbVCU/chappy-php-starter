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
    dateSelector
    dateTimeLocal
    email
    fileSelector
    interval
    month
    number
    password
    search
    tel
    text
    timeSelector
    urlInput
    week
imageButton
imageBlock
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
    int|float $min,
    int|float $max,
    mixed $value = '', 
    array $options = [],
    array $inputAttrs = [], 
    array $divAttrs = [],
    array $errors=[]
): string
```

Parameters:
- `int|float $min` - The minimum value for the interval.
- `int|float $max` - The maximum value for the interval.
Returns:
- `string` - A surrounding div and the input element of type range.

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