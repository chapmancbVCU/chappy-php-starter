<h1 style="font-size: 50px; text-align: center;">HasValidators Trait</h1>

## Table of contents
1. [Overview](#overview)
2. [Instance Variables](#instance-variables)
3. [Support Functions](#support-functions)
4. [Validator Callbacks](#validator-callbacks)
    * A. [alpha()](#alpha)
    * B. [alphaNumeric()](#alphaNumeric)
    * C. [between()](#between)
    * D. [classExists()](#class-exists)
    * E. [colonNotation()](#colonNotation)
    * F. [different()](#different)
    * G. [dotNotation()](#dotNotation)
    * H. [email()](#email)
    * I. [ip()](#ip)
    * J. [integer()](#integer)
    * K. [isPortUsed](#isPortUsed)
    * L. [list()](#list)
    * M. [lower()](#lower)
    * N. [match()](#match)
    * O. [max()](#max)
    * P. [min()](#min)
    * Q. [negative()](#negative)
    * R. [noSpecialChars()](#noSpecialChars)
    * S. [notReservedKeyword()](#notReservedKeyword)
    * T. [number()](#number)
    * U. [numeric](#numeric)
    * V. [required()](#required)
    * W. [positive()](#positive)
    * X. [queue()](#queue)
    * Y. [special()](#special)
    * Z. [testFilterNotation()](testFilterNotation)
    * A1. [upper()](#upper)
    * B1. [url()](#url)
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This trait is used by the `Console` and `FrameworkQuestion` class to validate input.  You can use the chain the available functions or provide them as string input to the `argOptionValidate` and `prompt` functions of the `Console` class.  Technically, you can chain them to the `choice` and `confirm` functions but it's not advisable.

<br>

## 2. Instance Variables <a id="instance-variables"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

**`protected array $errors`**

Contains a list of error messages.

<br>

**`protected string $fieldName`**

The name of the argument or option that is currently validated.  Use this if you have multiple inputs for your command.

<br>

**`protected array $reservedKeywords`**

Supports ability to avoid input that may conflict with a reserved keyword.

The list of reserved keywords is as follows:
```php
protected array $reservedKeywords = [
    // Reserved keywords
    'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch',
    'class', 'clone', 'const', 'continue', 'declare', 'default', 'die', 'do',
    'echo', 'else', 'elseif', 'empty', 'enddeclare', 'endfor', 'endforeach',
    'endif', 'endswitch', 'endwhile', 'eval', 'exit', 'extends', 'final',
    'finally', 'fn', 'for', 'foreach', 'function', 'global', 'goto', 'if',
    'implements', 'include', 'include_once', 'instanceof', 'insteadof',
    'interface', 'isset', 'list', 'match', 'namespace', 'new', 'or', 'print',
    'private', 'protected', 'public', 'readonly', 'require', 'require_once',
    'return', 'static', 'switch', 'throw', 'trait', 'try', 'unset', 'use',
    'var', 'while', 'xor', 'yield',

    // Predefined class names
    'self', 'parent', 'static',

    // Soft reserved / predefined constants
    'null', 'true', 'false',

    // Predefined classes worth avoiding
    'stdclass', 'exception', 'errorexception', 'closure', 'generator',
    'arithmetic error', 'typeerror', 'valueerror', 'stringable',

    // Enum related (PHP 8.1+)
    'enum',

    // Fiber related (PHP 8.1+)
    'fiber',
];
```

<br>

**`protected array $validators`**

A list of currently used validator callback functions.

<br>

## 3. Support Functions <a id="support-functions"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

**`public function addErrorMessage()`**

Adds a new error message to the $errors array.

Parameter:
- `string $message` -  The error message to be added to the $errors array.

<br>

**`public function displayErrorMessages()`**

Displays a list of all error messages.

<br>

**`public function fieldName()`**

Sets name of field to be validated.

Parameter:
- `string|array $fieldName` - The name of the field to be validated.

<br>

**`public function setValidator()`**

Adds validator to array of validators to be used.

Parameter:
- `callable $validator` - The anonymous function for a validator.

<br>

**`protected static function tokens()`**

Split on commas (tolerate spaces), normalize to lowercase, drop empties.  Useful for cases where you have a comma separated string.

Parameter:
- `string $data` - Comma separated strings of values to be converted into an array.

Returns:
- `array` - An array containing values originally found in comma separated string.

<br>

**`protected function validate()`**

Calls validator callbacks.  This function also ensures validators don't bleed into next question if instance is reused.

Parameter:
- `mixed $response` - The user answer.

Returns:
- `bool` - True if validation passed.  Otherwise, we return false.

<br>

## 4. Validator Callbacks <a id="validator-callbacks"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

### A. `alpha()` <a id="alphpa"></a>
Enforce rule where input must contain only alphabetic characters.

<br>

### B. `alphaNumeric()` <a id="alphaNumeric"></a>
Enforce rule where input must be alphanumeric characters.

<br>

### C. `between()` <a id="between"></a>
Ensures input is between within a certain range in length.

Parameter:
- `array $range` - 2 element array where position 0 is min and position 1 is max.

Usage:
```php
// FrameworkQuestion
$question->between([5, 10])->ask($message);

// Array parameter
['between:5:10']
```

<br>

### D. `classExists()` <a id="class-exists"></a>
Checks if class exists within the specified namespace.

Parameter:
- `string|array $namespace` - A string or an array containing one element with string for the namespace.

Usage:
```php
// FrameworkQuestion
$question->classExists(self::SEEDER_NAMESPACE)->ask($message);

// Array parameter
$attributes = ['classExists:'.self::SEEDER_NAMESPACE];
```

<br>

### E. `colonNotation()` <a id="colonNotation"></a>
Ensures response is in colon notation format.

<br>

### F. `different()` <a id="different"></a>
Enforce rule where response and $match parameter needs to be different.

Parameter:
- `mixed` - The value we want to compare.

Usage:
```php
// FrameworkQuestion
$question = new FrameworkQuestion($this->input, $this->output);
$message = "Enter a value:";
$response1 = $question->ask($message);

$message = "Enter a different value";
$response2 = $question->different($response1)->ask($message);

// Array parameter
$response3 = Controller::prompt($message, $this->question(), ["different:$response1"]);
```

<br>

### G. `dotNotation()` <a id="dotNotation"></a>
Ensures response is in dot notation format.

<br>

### H. `email()` <a id="email"></a>
Ensures input is a valid E-mail address.

<br>

### I. `ip()` <a id="ip"></a>
Enforce rule where input must be a valid IP address.

<br>

### J. `integer()` <a id="integer"></a>
Enforce rule where input must be an integer.

<br>

### K. `isPortUsed()` <a id="ip"></a>
Checks if a port on a particular host is in use.  Assists in verifying if a port is available for a serve command.  If the port is already in use an error message is presented to the user.

Parameter:
- `array $attributes` - An array that assumes index 0 is the host and index 1 is timeout variable which is set to 3 if not provided.

Usage:
```php
// FrameworkQuestion
$question->isPortUsed([$host, $timeout])->ask($message);

// Array parameter
["isPortUsed:$host:$timeout"]
```

<br>

### L. `list()` <a id="list"></a>
Ensure user inputs valid comma separated list of values.  The user must provide the following in the $attributes parameter:
1) Class containing full namespaced path
2) Name of function that returns an array of strings or a comma separated array of strings.
3) A string value in this array as an alias (optional)

Parameter:
- `array $attributes` - A : separate list in the following format: NamespaceToClass\\Class:Method:Alias.

Usage:
```php
// FrameworkQuestion
$question = new FrameworkQuestion($input, $output);
$message = "Enter comma separated list of channels.";
$response = $question->list([
    'Core\\Lib\\Notifications\\Notification', 'channelValues', 'all'
])->ask($message);

// Array parameter
$message = "Enter comma separated list of channels.";
$attributes = [
    'required', 
    'notReservedKeyword', 
    'list:Core\\Lib\\Notifications\\Notification:channelValues:all'
];
Notifications::argOptionValidate(
    $channels, 
    $message, 
    $this->question()
    $attributes, true
);
```

<br>

### M. `lower()` <a id="lower"></a>
Enforces rule when input must contain at least one lower case character.

<br>

### N. `match()` <a id="match"></a>
Enforce rule where response and $match parameter needs to match.

Parameter:
- `mixed` - The value we want to compare.

Usage:
```php
// FrameworkQuestion
$question = new FrameworkQuestion($this->input, $this->output);
$message = "Enter a value:";
$response1 = $question->ask($message);

$message = "Confirm value entered";
$response2 = $question->match($response1)->ask($message);

// Array parameter
$response3 = Controller::prompt($message, $this->question(), ["match:$response1"]);
```

<br>

### O. `max()` <a id="max"></a>
Ensures input meets requirements for maximum allowable length.

Parameter:
- `int|array $maxRule` - The maximum allowed size for input.

Usage:
```php
// FrameworkQuestion
$response1 = $question->max(50)->ask($message);

// Array parameter
['max:50']
```

<br>

### P. `min()` <a id="min"></a>
Ensures input meets requirements for minimum allowable length.

Parameter:
- `int|array $minRule` - The minimum allowed size for input.

Usage:
```php
// FrameworkQuestion
$response1 = $question->min(5)->ask($message);

// Array parameter
['min:5']
```

<br>

### Q. `negative()` <a id="negative"></a>
Enforces rule when input must be a negative number.

<br>

### R. `noSpecialChars()` <a id="noSpecialChars"></a>
Enforces rule when input must contain no special characters.

<br>

### S. `notReservedKeyword()` <a id="notReservedKeyword"></a>
Enforce rule when reserved keywords should be avoided.

<br>

### T. `number()` <a id="number"></a>
Enforces rule when input must contain at least one numeric character.

<br>

### U. `numeric()` <a id="numeric"></a>
Enforce rule where input must contain only numeric characters.

<br>

### V. `required()` <a id="required"></a>
Ensures required input is entered.

<br>

### W. `positive()` <a id="positive"></a>
Enforces rule when input must a positive number.

<br>

### X. `queue()` <a id="queue"></a>
Validates if queue exists in database or redis.

<br>

### Y. `special()` <a id="special"></a>
Enforces rule when input must contain at least one special character.

<br>

### Z. `testFilterNotation()` <a id="testFilterNotation"></a>
Ensures response is in colon notation format.

<br>

### A1. `upper()` <a id="upper"></a>
Enforces rule when input must contain at least one lower case character.

<br>

### B1. `url()` <a id="url"></a>
Enforce rule where input must be a valid URL.