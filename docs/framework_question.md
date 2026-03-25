<h1 style="font-size: 50px; text-align: center;">Framework Question</h1>

## Table of contents
1. [Overview](#overview)
2. [ask()](#ask)
    * A. [Setup](ask-setup)
    * B. [Validation](#validation)
    * C. [Modes](#modes)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `FrameworkQuestion` class provides the ability to ask the user questions.  The the functions you will be interacting with the most are as follows:

- `ask` - Asks the user a question that will receive a response
- `choice` - Asks user to choose among several available options
- `confirm` - Asks user questions that has a yes or no response.

<br>

## 2. `ask()` <a id="ask"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This function asks the user a question.  This function supports secret input and autocomplete.  An exception is thrown when both $secret and $anticipate are true.

Parameters:
- `$message` - The question to ask.
- `array $suggestions` - An array of suggestions for when `$anticipate`  is set to true.  An exception is thrown if this array is empty and  `$anticipate = true`.
- `string|bool|int|float|null $default` - The default value if the user does not provide an answer.

Returns:
- `mixed` - The user answer.  Null is returned if there is a timeout set and input is not received within set amount of time.

Throws:
- `FrameworkException` An an exception is thrown for the following two cases:
    - Both `$secret = true` and `$anticipate = true`
    - `$suggestions` is empty and `$anticipate = true`.

<br>

### A. Setup <a id="ask-setup"></a>
To use the `ask` function you need to perform the following steps:
1. Create a message.
2. Create an instance of the `FrameworkQuestion` class.
3. Call the `ask` function and track the response.

Example:
```php
$message = "Enter a response";
$question = new FrameworkQuestion($input, $output);
$response = $question->ask($message);
```

<br>

### B. Validation <a id="validation"></a>
The `FrameworkQuestion` class uses the `HasValidator` trait to support validation of input.  Simply chain validator functions to the `$question` object to perform validation.  The `Console` class has wrapper functions for these validators.  Links for those resources can be found at the end of this section.

<br>

**Using Validators**

Simply chain the validators as shown below:

```php
$question->required->between(10,50)->ask();
```

If input fails validation then a message is displayed.  You will be prompted to fix the issue after each attempt until all validation is successful.

Additional resources can be found here:
1. [hasValidators Trait](has_validators)
2. [Console Class](console_class)

<br>

### C. Modes <a id="modes"></a>
`FrameworkQuestion` supports 3 special modes that the user can set with chainable functions:
1. Secret
2. Timeout
3. Trimmable

<br>

**Secret**

Use this mode when you need to ask the user to enter sensitive information such as a password.

Example:
```php
$response = $question->secret()->ask($message);
```