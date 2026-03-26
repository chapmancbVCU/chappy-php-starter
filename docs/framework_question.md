<h1 style="font-size: 50px; text-align: center;">Framework Question</h1>

## Table of contents
1. [Overview](#overview)
2. [ask()](#ask)
    * A. [Setup](ask-setup)
    * B. [Validation](#validation)
    * C. [Modes](#modes)
3. [choice()](#choice)
4. [confirm()](#confirm)

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
2. Anticipate
3. Timeout
4. Disable Trimmable

Once input is entered secret, anticipate, and trimmable modes are toggled off.

<br>

**Secret**

Use this mode when you need to ask the user to enter sensitive information such as a password.

Example:
```php
$response = $question->secret()->ask($message);
```

<br>

**Anticipate**

Anticipate supports the ability to show suggestions for responses based on how the user types.  Use the `anticipate()` chainable function with an array of suggestions for the `ask` function's `$suggestion` parameter to take advantage of this feature.

Example:
```php
$suggestions = ['Option A', 'Suggestion B'];
$response = $question->anticipate()->ask($message, $suggestions);
```

<br>

**Timeout**

If you need to establish a timeout period for a prompt use the `timeout(int $timeout)` chainable function.

Example:
```php
$response = $question->timeout(30)->ask($message);
```

<br>

**Disable Trimmable**

The term "trimmable" in Symfony console specifically refers to a feature within the Question helper that controls whether the user's input should have leading/trailing whitespace removed (trimmed) before being processed.

Disable trimmable with the `disableTrimmable()` chainable function.

Example:
```php
$response = $question->disableTrimmable()->ask($message);
```

<br>

## 3. `choice()` <a id="choice"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This function asks the user a question that requires to choose among a set of specified options.

Parameters:
- `$message` - The question to ask.
- `array $choices` - An array of choices.
- `string|bool|int|float|null $default` - The default value if the user does not provide an answer.

Example:
```php
$message = "Choose one of the following:";
$question = new FrameworkQuestion($input, $output);
$choices = ['Option A', 'Option B', 'Option C'];
$response = $question->choice($message, $choices, $choices[0]);
```

Returns:
- `string` - The user answer

<br>

## 4. `confirm()` <a id="confirm"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This function is used if you want to confirm that the user should proceed with a certain action.

Parameters:
- `$message` - The question to ask.
- `string|bool|int|float|null $default` - The default value if the user does not provide an answer.

Returns:
- `string` - The user answer

Example:
```php
$message = "Are you sure you want to continue (y/n)";
$question = new FrameworkQuestion($input, $output);
if($question->confirm($message)) {
    // do something
}
```