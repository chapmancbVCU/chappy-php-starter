<h1 style="font-size: 50px; text-align: center;">Console Class</h1>

## Table of contents
1. [Overview](#overview)

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

