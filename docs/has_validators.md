<h1 style="font-size: 50px; text-align: center;">HasValidators Trait</h1>

## Table of contents
1. [Overview](#overview)
2. [Instance Variables](#instance-variables)

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

