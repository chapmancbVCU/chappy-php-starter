<h1 style="font-size: 50px; text-align: center;">Unit Tests</h1>

## Table of contents
1. [Overview](#overview)
2. [Creating Tests](#creating-tests)
3. [Running Tests](#running-tests)\

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The Chappy.php framework has support for unit testing.  

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

**Application Test Support**
To create a PHPUnit test class that extends `ApplicationTestCase` use the `--app` flag.  This version of the PHPUnit test class adds support for migrations and database seeding.

<br>


## 3. Running Tests <a id="running-tests"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Run all available tests.
```sh
php console test
```

**Run Tests By File**
Run all tests in a file that exists within the feature, unit, or both test suites.
```sh
php console test ${fileName}
```

**Run A Particular Test**
Run a specific test in a file.
```sh
php console test ${fileName}::${functionName}
```

If you have the same function in a class with the same name inside both test suites only the one found within the unit test suite will be executed.

**Run A Test Suite**
Run all test within a particular test suite by adding the `--unit` and/or `--feature` flags.

**Run Specific Test File Within A Suite**
You can run all test within a specific test file for an individual suite by specifying the file name and adding the `--unit` and/or `--feature` flags.