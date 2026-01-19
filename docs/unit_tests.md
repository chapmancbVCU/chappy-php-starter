<h1 style="font-size: 50px; text-align: center;">PHPUnit</h1>

## Table of contents
1. [Overview](#overview)
2. [TestBuilderInterface](#test-builder)
3. [TestRunner Class](#test-runner)
4. [Building A Test Suite](#test-suite)
    * A. [Test Runner](#test-runner)
    * B. [Test Builder](#test-builder)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework natively supports unit testing with PHPUnit for PHP and Vitest for JavaScript/React.js files.  Chappy.php also exposes its console based API so users can integrate other test suites into their projects.

The API consists of the following:
- `TestBuilderInterface` - An interface that all builders should implement
- `TestRunner` - Super class that contains functions for running unit tests.

<br>

## 2. TestBuilderInterface <a id="test-builder"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
TestBuilderInterface contains the `makeTest` function which is required for all test builders to implement.  The signature of this function is described below.

Parameters:
- `string $testName` - The name of the test
- `InputInterface $input` - The Symfony InputInterface object

Return:
- `int` - A value that indicates success, invalid, or failure

<br>

## 3. TestRunner Class <a id="test-runner"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The TestRunner class contains functions available for you to use in your own child classes.

**Constructor**

Parameter:
- `OutputInterface $output` - This enables logging of test output to console.

<br>

**areAllSuitesEmpty**

Test to ensure there is not an empty test suite.
Parameter:
- `array $testSuites` - The collection of all available test suites.  Best practice is to use const provided by child class.

Returns:
- `bool` - True if all test suites are empty.  Otherwise, we return false.

<br>

**allTests**

Performs all available tests.
Parameters:
- `array $testSuites` - An array of test suite paths.
- `string|array $extensions` - A string or an array of supported file extensions.  Best practice is to use const provided by child class.
- `string $testCommand` - The command for running the tests.

Returns:
- `int` - A value that indicates success, invalid, or failure.

<br>

**getAllTestsInSuite**

Retrieves all files in test suite so they can be run.
Parameters:
- `string $path` - Path to test suite.
- `string $ext` - File extension to specify between php and js related tests.  Best practice is to use const provided by child class.

Returns:
- `array` - The array of all filenames in a particular directory.

<br>

**runTest**

Runs the unit test for your testing suite.
Parameter:
- `string $test` - The test to be performed.
- `string $testCommand` - The test command to be executed.
<br>

**selectByTestName**

Supports ability to run test by class/file name.
Parameters:
- `string $testArg` - The name of the class/file.
- `array $testSuites` - An array of test suite paths.  Best practice is to use const provided by child class.
- `string|array $extensions` - A string or an array of supported file  extensions.  Best practice is to use const provided by child class.

Returns:
- `int` - A value that indicates success, invalid, or failure.

<br>

**singleFileWithinSuite**

Performs testing against a single class within a test suite.
Parameters:
- `string $testArg` - The name of the test file without extension.
- `string $testSuite` - The name of the test suite.  Best practice is to use const provided by child class.
- `string $ext` - The file extension.  Best practice is to use const provided by child class.
- `string $command` - The test command.  Best practice is to use const provided by child class.

Returns:
- `int` - A value that indicates success, invalid, or failure.

<br>

**testExists**

Determine if test file exists in any of the available test suites.
Parameters:
- `string $name` - The name of the test we want to confirm if it exists.
- `array $testSuites` - The array of test suites.  Best practice is to use const provided by child class.
- `string|array $extensions` - A string or an array of supported file extensions.  Best practice is to use const provided by child class.

Returns:
- `bool` - True if test does exist.  Otherwise, we return false.

<br>

**testIfSame**

Enforces rule that classes/files across test suites should be unique for filtering.
Parameters:
- `string $name` - name of the test class to be executed.
- `array $testSuites` - The array of test suites.  Best practice is to use const provided by child class.
- `string $extension` - A string or an array of supported file extensions.  Best practice is to use const provided by child class.

Returns:
- `bool` - True if the class or file name exists in multiple test suites.  Otherwise, we return false.

<br>

**testSuite**

Run all test files in an individual test suite.
Parameters:
- `array $collection` - All classes in a particular test suite.
- `string $testCommand` - The test command to be executed.

Returns:
- `int` - A value that indicates success, invalid, or failure.

<br>

**testSuiteStatus**

Determines if execution of a test suite(s) is successful.  The result is determined by testing if the status value is set and  its integer value is equal to Command::SUCCESS.
Parameter:
- `array<int>` - $suiteStatuses Array of integers that indicates a test is successful. 

Returns:
- `bool` - True if execution is successful.  Otherwise, we return false.

<br>

**verifyFilterSyntax**

Ensure filter syntax is correct.  Does not test if only one : is in string.
Parameter:
- `string $testArg`  - The name of the test file with filter.

Returns:
- `bool` - True if filter syntax is correct.  Otherwise, we return false.

<br>

## 4. Building A Test Suite <a id="test-suite"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
To add support for another 3rd party framework you will need the following:
- Test builder class
- Test runner class
- Custom commands for making tests
- Custom command for running the tests

<br>

### A. Test Builder <a id="test-builder"></a>
The command line interface wrapper for your testing suite will need two support files.  They are a builder and a runner.

To create a builder run the following command:
```bash
php console make:test:builder <builder-name>
```

The file is created at `app\TestBuilder\`.  This class implements the `TestBuilderInterface`.  The output for this class is shown below:

```php
<?php
namespace App\Testing;

use Console\Helpers\Testing\TestBuilderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

class ExampleBuilder implements TestBuilderInterface {

    public static function makeTest(string $testName, InputInterface $input): int {

        return Command::SUCCESS;
    }
}
```

The primary goal of this class is to be used by a custom command that is used to create new unit test files.  The `makeTest` function for PHPUnit is shown below:

```php
/**
 * Creates a new test class.  When --feature flag is provided a test 
 * feature class is created.
 *
 * @param string $testName The name for the test.
 * @param InputInterface $input The Symfony InputInterface object.
 * @return int A value that indicates success, invalid, or failure.
 */
public static function makeTest(string $testName, InputInterface $input): int {
    $testSuites = [PHPUnitRunner::FEATURE_PATH, PHPUnitRunner::UNIT_PATH];
    
    if(PHPUnitRunner::testExists($testName, $testSuites, PHPUnitRunner::TEST_FILE_EXTENSION)) {
        Tools::info(
            "File with the name '{$testName}' already exists in one of the supported test suites", Logger::ERROR, 
            Tools::BG_RED
        );
        return Command::FAILURE;
    }

    if($input->getOption('feature')) {
        return Tools::writeFile(
            ROOT.DS.PHPUnitRunner::FEATURE_PATH.$testName.PHPUnitRunner::TEST_FILE_EXTENSION,
            PHPUnitStubs::featureTestStub($testName),
            'Test'
        );
    } else {
        return Tools::writeFile(
            ROOT.DS.PHPUnitRunner::UNIT_PATH.$testName.PHPUnitRunner::TEST_FILE_EXTENSION,
            PHPUnitStubs::unitTestStub($testName),
            'Test'
        );
    }

    return Command::FAILURE;
}
```

The main workflow is to test if a test case file with the same name exists in one your test suites and to process any flags that direct creation of those files.  With PHPUnit we support `unit` and `feature` tests.

<br>

### B. Test Runner <a id="test-runner"></a>
