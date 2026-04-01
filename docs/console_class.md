<h1 style="font-size: 50px; text-align: center;">Console Class</h1>

## Table of contents
1. [Overview](#overview)
2. [argOptionValidate()](#arg_option_validate)
3. [prompt()](#prompt)
4. [choice()](#choice)
5. [confirm()](#confirm)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This class contains wrapper functions for the [`FrameworkQuestion](framework_question) class and provides the ability to use validators for argument and option input provided by the `InputInterface` object.

**Basic Usage**
Using the long form method you can chain validator functions provided by the `HasValidators` trait or use the wrapper functions for ask.  

The long form example is shown below:
```php
self::getInstance()->required()
            ->noSpecialChars()
            ->alpha()
            ->notReservedKeyword()
            ->max(50)
            ->validate($argument);
```

or

```php
$console = new Console("fieldName");
$console()->noSpecialChars()
        ->alpha()
        ->notReservedKeyword()
        ->max(50)
        ->validate($argument);
```

The `getInstance` function and constructor accepts an optional `$fieldName` parameter if you want to set the field name in output messages for validation.

<br>

## 2. `argOptionValidate()` <a id="arg_option_validate"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Primary validator wrapper for processing `InputInterface` arguments and options.  If validation fails then `prompt` is called internally to receive followup input.  

By default, the following validators are used:
- `required()`
- `noSpecialChars()`
- `alpha()`
- `notReservedKeyword()`

Parameters:
- `string $field` - The reference to the value to be validated.
- `string $message` - The message to present to the user.
- `FrameworkQuestion $question` - Instance of FrameworkQuestion class.
- `array $attributes` - An array of additional validators.
- `bool $defaultNone` -  When set to true user will have to specify all validators.

Example:
```php
$controllerName = $input->getArgument('controller-name');
if($controllerName) {
    $attributes = [
        'required',
        'noSpecialChars',
        'alpha',
        'notReservedKeyword',
        'max:50', 
        'fieldName:controller-name'
    ];
    Controller::argOptionValidate(
        $controllerName, 
        Controller::PROMPT_MESSAGE, 
        $input, 
        $output, 
        $attributes,
        true
    );
}
```

The above example is from the `make:controller` command.  The `Controller` class extends the `Console` class so this function is called statically with `Controller` instead.  Additional validators are provided with the `$attributes` array as strings.  Any parameters needed for the validator is separated by a `:` from the validator or any additional parameters.  The `fieldName` is not a validator but a special helper function that allows the user to enter the field name for messaging purposes.

Since we overrode the default validators we supplied all validators in the `$attributes` array.

## 3. `prompt()` <a id="prompt"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
A direct wrapper function for the `FrameworkQuestion::ask()` function.

By default, the following validators are used:
- `required()`
- `noSpecialChars()`
- `alpha()`
- `notReservedKeyword()`

Parameters:
- `string $message` - The message to present to the user.
- `FrameworkQuestion $question` - Instance of FrameworkQuestion class.
- `array $attributes` - An array of additional validators.
- `array $suggestions` - An array of suggestions for when `$anticipate` is set to `true`.  An exception is thrown if this array is empty and `$anticipate = true`.
- `string|bool|int|float|null $default` - The default value if the user does not provide an answer.
- `bool $defaultNone`  - When set to true user will have to specify all validators and attributes.

Returns:
- `mixed` - The user response.

Example:
```php
$attributes = [
    'required',
    'noSpecialChars',
    'alpha',
    'notReservedKeyword',
    'max:50', 
    'fieldName:controller-name'
];
$response = self::prompt(self::PROMPT_MESSAGE, $this->question(), $attributes, [], null, true);
```

This function supports all validators supported by the `HasValidators` trait.  You can also modify the available modes supported by the `FrameworkQuestion` class.

<br>

**Secret**

Use this mode when you need to ask the user to enter sensitive information such as a password.

Example:
```php
$response = self::prompt(self::PROMPT_MESSAGE, $this->question(), ['secret']);
```

<br>

**Anticipate**

Anticipate supports the ability to show suggestions for responses based on how the user types.

Example:
```php
$suggestions = ['Option A', 'Suggestion B'];
$response = self::prompt(self::PROMPT_MESSAGE, $this->question(), ['anticipate'], $suggestions);
```

<br>

**Timeout**

If you need to establish a timeout period for a prompt use the `timeout` function.

Example:
```php
$response = self::prompt(self::PROMPT_MESSAGE, $this->question(), ['timeout:30']); 
```

<br>

**Disable Trimmable**

The term “trimmable” in Symfony console specifically refers to a feature within the Question helper that controls whether the user’s input should have leading/trailing whitespace removed (trimmed) before being processed.

Example:
```php
$response = self::prompt(self::PROMPT_MESSAGE, $this->question(), ['disableTrimmable']); 
```

<br>

## 4. `choice()` <a id="choice"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This function asks the user a question that requires to choose among a set of specified options.

Parameters:
- `string $message` The message to present to the user.
- `array $choices` An array of choices.
- `FrameworkQuestion $question` - Instance of FrameworkQuestion class.
- `string|boolean|integer|float|null|null $default` The default value if the user does not provide an answer.

Returns:
- `mixed` - The user answer.

Example:
```php
$message = "Which log do you want to delete (default: App)?";
$options = ['App', 'CLI', 'PHPUnit', 'All'];
return self::choice($message, $options, $this->question(), $options[0]);
```

<br>

## 5. `confirm()` <a id="confirm"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This function is used if you want to confirm that the user should proceed with a certain action.

Parameters:
- `string $message` - The message to present to the user.
- `FrameworkQuestion $question` - Instance of FrameworkQuestion class.
- `string|bool|int|float|null $default` - The default value if the user does not provide an answer.

Returns:
- `mixed` - The user answer.

Example:
```php
$message = "Do you want to create a menu specific to this layout? (y/n)";
if(self::confirm($message, $this->question())) {
    self::makeMenu($layoutName);
    return $layoutName;
}
```