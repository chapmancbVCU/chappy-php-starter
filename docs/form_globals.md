<h1 style="font-size: 50px; text-align: center;">Global Helpers</h1>

## Table of contents
1. [Overview](#overview)
2. [button()](#button)
3. [buttonBlock()](#buttonBlock)
checkboxLabelLeft
checkboxLabelRight
checkboxGroup
csrf
datalists
    dataListColor
    dataListDate
    dataListDateTimeLocal
    dataListInterval
    dataListMonth
    dataListText
    dataListTime
    dataListWeek
errorBag
hidden
input
    color
    confirm
    currency
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