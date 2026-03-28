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
    * L. [list()](#list)
    * M. [lower()](#lower)
    * N. [match()](#match)
    * O. [max()](#max)
    * P. [min()](#min)
    * Q. [negative()](#negative)
    * R. [number()](#number)
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This trait is used by the `Console` and `FrameworkQuestion` class to validate input.  You can use the chain the available functions or provide them as string input to the `argOptionValidate` and `prompt` functions of the `Console` class.  Technically, you can chain them to the `choice` and `confirm` functions but it's not advisable.

<br>

## 2. Instance Variables <a id="instance-variables"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

**protected array $errors**

Contains a list of error messages.

<br>

**protected string $fieldName**

The name of the argument or option that is currently validated.  Use this if you have multiple inputs for your command.

<br>

**protected array $reservedKeywords**

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

**protected array $validators**

A list of currently used validator callback functions.

<br>

## 3. Support Functions <a id="support-functions"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

**public function addErrorMessage($string $message): void**

Adds a new error message to the $errors array.

Parameter:
- `string $message` -  The error message to be added to the $errors array.

<br>

**public function displayErrorMessages(): void**

Displays a list of all error messages.

<br>

**public function fieldName(string\|array $fieldName): static**

Sets name of field to be validated.

Parameter:
- `string|array $fieldName` - The name of the field to be validated.

<br>

**public function setValidator(callable $validator): static**

Adds validator to array of validators to be used.

Parameter:
- `callable $validator` - The anonymous function for a validator.

<br>

**protected static function tokens(string $data): array**

Split on commas (tolerate spaces), normalize to lowercase, drop empties.  Useful for cases where you have a comma separated string.

Parameter:
- `string $data` - Comma separated strings of values to be converted into an array.

Returns:
- `array` - An array containing values originally found in comma separated string.

<br>

**protected function validate(mixed $response): bool**

Calls validator callbacks.  This function also ensures validators don't bleed into next question if instance is reused.

Parameter:
- `mixed $response` - The user answer.

Returns:
- `bool` - True if validation passed.  Otherwise, we return false.

<br>

## 4. Validator Callbacks <a id="validator-callbacks"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

### A. alpha() <a id="alphpa"></a>
Enforce rule where input must contain only alphabetic characters.

<br>

### B. alphaNumeric() <a id="alphaNumeric"></a>
Enforce rule where input must be alphanumeric characters.

<br>

### C. between(array $range) <a id="between"></a>
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

### D. classExists(array $namespace) <a id="class-exists"></a>
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

### E. colonNotation() <a id="colonNotation"></a>
Ensures response is in colon notation format.

<br>

### F. different(mixed $data) <a id="different"></a>
Enforce rule where response and $match parameter needs to be different.

Parameter:
- `mixed` - The value we want to compare.

Usage:
```php
// FrameworkQuestion
$question = new FrameworkQuestion($input, $output);
$message = "Enter a value:";
$response1 = $question->ask($message);

$message = "Enter a different value";
$response2 = $question->different($response1)->ask($message);

// Array parameter
$response3 = Controller::prompt($message, $input, $output, ["different:$response1"]);
```

<br>

### G. dotNotation() <a id="dotNotation"></a>
Ensures response is in dot notation format.

<br>

### H. email() <a id="email"></a>
Ensures input is a valid E-mail address.

<br>

### I. ip() <a id="ip"></a>
Enforce rule where input must be a valid IP address.

<br>

### J. integer() <a id="integer"></a>
Enforce rule where input must be an integer.

<br>

### L. list(array $attributes) <a id="list"></a>
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
    $input, 
    $output, 
    $attributes, true
);
```

<br>

### M. lower() <a id="lower"></a>
Enforces rule when input must contain at least one lower case character.

<br>

### N. match(mixed $match) <a id="match"></a>
Enforce rule where response and $match parameter needs to match.

Parameter:
- `mixed` - The value we want to compare.

Usage:
```php
// FrameworkQuestion
$question = new FrameworkQuestion($input, $output);
$message = "Enter a value:";
$response1 = $question->ask($message);

$message = "Confirm value entered";
$response2 = $question->match($response1)->ask($message);

// Array parameter
$response3 = Controller::prompt($message, $input, $output, ["match:$response1"]);
```

<br>

### O. max(int\|array $maxRule) <a id="max"></a>
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

### P. min(int\|array $maxRule) <a id="min"></a>
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

### Q. negative() <a id="negative"></a>
Enforces rule when input must be a negative number.

<br>

### R. number() <a id="number"></a>
Enforces rule when input must contain at least one numeric character.