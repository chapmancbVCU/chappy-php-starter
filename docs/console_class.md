<h1 style="font-size: 50px; text-align: center;">Console Class</h1>

## Table of contents
1. [Overview](#overview)
2. [argOptionValidate()](#arg_option_validate)
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

## 2. argOptionValidate() <a id="arg_option_validate"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Primary validator wrapper for processing `InputInterface` arguments and options.  If validation fails then `prompt` is called internally to receive followup input.  

By default, the following validators are used:
- `required()`
- `noSpecialChars()`
- `alpha()`
- `notReservedKeyword()`

Parameters:
- `string $field` - The reference to the value to be validated.
- `string $message` - The message to present to the user.
- `InputInterface $input` - The Symfony InputInterface object.
- `OutputInterface $output` - The Symfony OutputInterface object.
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

