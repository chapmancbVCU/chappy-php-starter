<h1 style="font-size: 50px; text-align: center;">PHPUnit</h1>

## Table of contents
1. [Overview](#overview)
2. [Creating Tests](#creating-tests)
3. [Running Tests](#running-tests)
4. [PHPUnit Assertions](#phpunit-assertions)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This guide provides an overview on how to use the console Command Line Interface (CLI) for running PHPUnit related unit tests.

<br>

## 2. Creating Tests <a id="creating-tests"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
You can make your own PHPUnit test class by running the following command:

```sh
php console make:test ${testName}
```

By default the `make:test` places the file under the unit test suite.  To create a feature test run:

```sh
php console make:test ${testName} --feature
```

This version of the PHPUnit test class adds support for migrations and database seeding.

**Naming Conventions**
- A PHPUnit enforced rule requires that each test case within your class begins with the word `test`.
- In order to properly use filtering file this framework prevents you from creating files/classes with the same name across test suites with the `make:test` command.  The filtering command also enforces this rule.
- The name of your test case functions in all of your classes must be unique.  Otherwise you will get confusing messages regarding assertions even when filtering.

<br>

## 3. Running Tests <a id="running-tests"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Run all available tests.
```sh
php console test
```

**Run Tests By File**

Run all tests in a file that exists within the feature and unit test suites.
```sh
php console test ${fileName}
```

**Run A Particular Test**

Run a specific test in a file.
```sh
php console test ${fileName}::${functionName}
```

**Running Tests With PHPUnit**

You can use `vendor/bin/phpunit` to bypass the console's `test` command.

**Supported PHPUnit flags**

The following flags are supported without running PHPUnit directly using `vendor/bin/phpunit`.
<a id="coverage"></a>

🧹 Coverage and Logging

| Flag                       | Description                             |
| -------------------------- | --------------------------------------- |
| `--coverage-text`          | Output code coverage summary to console |

<a id="display-flags"></a>

✅ Output / Display Flags

| Flag                     | Description                                                      |
| ------------------------ | ---------------------------------------------------------------- |
| `--debug`                | Show debugging info for each test (e.g., method names being run) |
| `--display-depreciations`| Show deprecated method warnings                                  |
| `--display-errors`       | Show errors (on by default)                                      |
| `--display-incomplete`   | Show incomplete tests in summary                                 |
| `--display-skipped`      | Show skipped tests in summary                                    |
| `--fail-on-incomplete`   | Mark incomplete tests as failed                                  |
| `--fail-on-risky`        | Fail if risky tests are detected                                 |
| `--testdox`              | Print readable test names (e.g., "It returns true on success")   |

<a id="behavior-flags"></a>

🔁 Execution / Behavior Flags

| Flag                   | Description                  |
| ---------------------- | ---------------------------- |
| `--random-order`       | Randomize test order         |
| `--reverse-order`      | Run tests in reverse order   |
| `--stop-on-error`      | Stop on error                |
| `--stop-on-failure`    | Stop as soon as a test fails |
| `--stop-on-incomplete` | Stop on incomplete test      |
| `--stop-on-risky`      | Stop on risky test           |
| `--stop-on-skipped`    | Stop on skipped test         |
| `--stop-on-warning`    | Stop on warning              |


If you have the same function in a class with the same name inside both test suites only the one found within the unit test suite will be executed.

<a id="test-suite"></a>

**Run A Test Suite**

Run all test within a particular test suite by adding the `--unit` and/or `--feature` flags.  You can target more than one suite at a time by using more than one flag.

**Run Specific Test File Within A Suite**

You can run all test within a specific test file for an individual suite by specifying the file name and adding the `--unit` or `--feature` flags.  You can target more than one suite at a time by using more than one flag.

<br>

## 4. PHPUnit Assertions <a id="phpunit-assertions"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
PHPUnit provides a rich set of built-in assertions you can use in your tests. These are all supported out of the box in your test classes (like ApplicationTestCase) because they extend PHPUnit\Framework\TestCase.

Here’s a categorized list of commonly used PHPUnit assertions (as of PHPUnit 11.x):

✅ Equality & Identity

| Assertion                             | Description                               |
| ------------------------------------- | ----------------------------------------- |
| `assertEquals($expected, $actual)`    | Checks if two values are equal (==)       |
| `assertSame($expected, $actual)`      | Checks if two values are identical (===)  |
| `assertNotEquals($expected, $actual)` | Asserts that two values are not equal     |
| `assertNotSame($expected, $actual)`   | Asserts that two values are not identical |

<br>

🚫 Null / Empty / Boolean

| Assertion                 | Description                                              |
| ------------------------- | -------------------------------------------------------- |
| `assertNull($actual)`     | Checks if a value is `null`                              |
| `assertNotNull($actual)`  | Checks if a value is not `null`                          |
| `assertTrue($condition)`  | Checks if condition is `true`                            |
| `assertFalse($condition)` | Checks if condition is `false`                           |
| `assertEmpty($actual)`    | Checks if a variable is empty (e.g., `[]`, `""`, `null`) |
| `assertNotEmpty($actual)` | Checks if a variable is not empty                        |

<br>

🧵 Type Assertions

| Assertion                                   | Description                                                |
| ------------------------------------------- | ---------------------------------------------------------- |
| `assertInstanceOf($expectedClass, $object)` | Asserts object is an instance of a class                   |
| `assertIsArray($actual)`                    | Asserts variable is an array                               |
| `assertIsString($actual)`                   | Asserts variable is a string                               |
| `assertIsInt($actual)`                      | Asserts variable is an integer                             |
| `assertIsBool($actual)`                     | Asserts variable is a boolean                              |
| `assertIsFloat($actual)`                    | Asserts variable is a float                                |
| `assertIsCallable($actual)`                 | Asserts variable is callable                               |
| `assertIsObject($actual)`                   | Asserts variable is an object                              |
| `assertIsScalar($actual)`                   | Asserts variable is a scalar (int, float, string, or bool) |

<br>

🧮 Array / Count / Contains

| Assertion                             | Description                                          |
| ------------------------------------- | ---------------------------------------------------- |
| `assertCount($expectedCount, $array)` | Asserts array has expected number of elements        |
| `assertContains($needle, $haystack)`  | Asserts that a value exists in array or string       |
| `assertArrayHasKey($key, $array)`     | Asserts key exists in an array                       |
| `assertArrayNotHasKey($key, $array)`  | Asserts key does not exist in array                  |
| `assertContainsOnly($type, $array)`   | Asserts array contains only values of a certain type |

<br>

⚠️ Exception / Error / Output

| Assertion                                        | Description                                   |
| ------------------------------------------------ | --------------------------------------------- |
| `expectException(Exception::class)`              | Expects an exception to be thrown             |
| `expectExceptionMessage('message')`              | Expects exception message to match            |
| `expectExceptionCode(123)`                       | Expects exception code to match               |
| `expectOutputString('expected output')`          | Asserts output matches string                 |
| `assertStringContainsString($needle, $haystack)` | Asserts that a string contains another string |

<br>

⏱️ Performance / Custom

| Assertion                                           | Description                                  |
| --------------------------------------------------- | -------------------------------------------- |
| `assertLessThan($expected, $actual)`                | Asserts that actual is less than expected    |
| `assertGreaterThan($expected, $actual)`             | Asserts that actual is greater than expected |
| `assertMatchesRegularExpression($pattern, $string)` | Asserts that a string matches regex          |
| `assertThat($value, $constraint)`                   | Use custom constraints (advanced)            |
