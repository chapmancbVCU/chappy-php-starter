<h1 style="font-size: 50px; text-align: center;">Unit Tests</h1>

## Table of contents
1. [Overview](#overview)
2. [TestBuilderInterface](#test-builder)
3. [TestRunner Class](#test-runner)
4. [Building A Test Suite](#test-suite)
    * A. [Test Runner](#test-runner)
    * B. [Test Builder](#test-builder)
5. [Running Tests](#running-tests)

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
- Choose a location for your test cases

This framework already has locations for testing that you can use:
- PHPUnit - `tests\`
- Vitest - `resources\js\tests\`

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

The primary goal of this class is to be used by a custom command that is used to create new unit test files.  Use the `php console make:command <command-name>` command to create your command.  The `makeTest` function for PHPUnit is shown below:

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
A test runner class is used to support the actual execution of your test cases.  Run the following command to generate a test runner:

```bash
php console make:test:runner <runner-name>
```

The this file is created at `app\testing\`.  An example is shown below:

```php
<?php
declare(strict_types=1);
namespace App\Testing;

use Console\Helpers\Tools;
use Console\Helpers\Testing\TestRunner;
use Core\Lib\Logging\Logger;
use Core\Lib\Utilities\Arr;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ExampleRunner extends TestRunner {
    /**
     * The array of options allowed as input for the test command.
     */
    public const ALLOWED_OPTIONS = [];

    /**
     * The command for Unit Testing Framework.
     */
    public const TEST_COMMAND = '';

    /**
     * Constructor
     *
     * @param InputInterface $input The Symfony InputInterface object.
     * @param OutputInterface $output The Symfony OutputInterface object.
     */
    public function __construct(InputInterface $input, OutputInterface $output) {
        $this->inputOptions = self::parseOptions($input);
        parent::__construct($output);
    }

    /**
     * Parses unit test related arguments and ignore Symfony arguments.
     *
     * @param InputInterface $input Instance of InputInterface from command.
     * @return string A string containing the arguments to be provided to 
     * your testing framework.
     */
    public static function parseOptions(InputInterface $input): string { 
        $args = [];

        foreach(self::ALLOWED_OPTIONS as $allowed) {
            if($input->hasOption($allowed) && $input->getOption($allowed)) {
                switch($allowed) {
                    default;
                        $args[] = '--' . $allowed;
                        break;
                }
            }
        }
        return (Arr::isEmpty($args)) ? '' : ' ' . implode(' ', $args);
    }
}
```

#### Description of constants:
- `public const ALLOWED_OPTIONS = []` - Allowed options you want to support
- `public const TEST_COMMAND` - The command for the testing framework

<br>

#### Functions

**construct**

Parameters:
- `InputInterface $input` - Symfony InputInterface object. Needed to support option flags
- `OutputInterface $output` - The Symfony OutputInterface object.

<br>

**parseOptions**

Parameters:
- `InputInterface $input` - Instance of InputInterface from command.

Returns:
- `string` - A string containing the arguments to be provided to your testing framework.

<br>

#### User Defined Items

To implement your runner you will need to implement the following:
- A full path to each of the available testing suites.
- File extensions for each type of test file.  If you are supporting a testing framework you will need to define `.js` and `.jsx`.
- If you want filtering you will need to define that function as well.

<br>

#### Complete Example
Below is a complete example for PHPUnit.

```php
<?php
declare(strict_types=1);
namespace Console\Helpers\Testing;

use Console\Helpers\Tools;
use Core\Lib\Logging\Logger;
use Core\Lib\Utilities\Arr;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Supports PHPUnit testing operations.
 */
final class PHPUnitRunner extends TestRunner {
    /**
     * The array of options allowed as input for the test command.
     */
    public const ALLOWED_OPTIONS = [
        'coverage',
        'debug',
        'display-deprecations',
        'display-errors',
        'display-incomplete',
        'display-skipped',
        'fail-on-incomplete',
        'fail-on-risky',
        'random-order',
        'reverse-order',
        'stop-on-error',
        'stop-on-failure',
        'stop-on-incomplete',
        'stop-on-risky',
        'stop-on-skipped',
        'stop-on-warning',
        'testdox',
    ];

    /**
     * The path for feature tests.
     */
    public const FEATURE_PATH = 'tests'.DS.'Feature'.DS;

    /**
     * The command for PHPUnit
     */
    public const TEST_COMMAND = 'php vendor/bin/phpunit ';

    /**
     * File extension for PHPUnit unit tests.
     */
    public const TEST_FILE_EXTENSION = ".php";
    
    /**
     * The path for unit tests.
     */
    public const UNIT_PATH = 'tests'.DS.'Unit'.DS;

    /**
     * Constructor
     *
     * @param InputInterface $input The Symfony InputInterface object.
     * @param OutputInterface $output The Symfony OutputInterface object.
     */
    public function __construct(InputInterface $input, OutputInterface $output) {
        $this->inputOptions = self::parseOptions($input);
        parent::__construct($output);
    }

    /**
     * Parses PHPUnit related arguments and ignore Symfony arguments.
     *
     * @param InputInterface $input Instance of InputInterface from command.
     * @return string A string containing the arguments to be provided to 
     * PHPUnit.
     */
    public static function parseOptions(InputInterface $input): string {
        $args = [];

        foreach(self::ALLOWED_OPTIONS as $allowed) {
            if($input->hasOption($allowed) && $input->getOption($allowed)) {
                switch ($allowed) {
                    case 'coverage':
                        $args[] = '--coverage-text';
                        break;
                    case 'debug':
                        $args[] = '--debug';
                        break;
                    case 'display-deprecations':
                        $args[] = '--display-deprecations';
                        break;
                    case 'display-errors':
                        $args[] = '--display-errors';
                        break;
                    case 'display-incomplete':
                        $args[] = '--display-incomplete';
                        break;
                    case 'display-skipped':
                        $args[] = '--display-skipped';
                        break;
                    case 'fail-on-incomplete':
                        $args[] = '--fail-on-incomplete';
                        break;
                    case 'fail-on-risky':
                        $args[] = '--fail-on-risky';
                        break;
                    case 'random-order':
                        $args[] = '--random-order';
                        break;
                    case 'reverse-order':
                        $args[] = '--reverse-order';
                        break;
                    case 'stop-on-error':
                        $args[] = '--stop-on-error';
                        break;
                    case 'stop-on-failure':
                        $args[] = '--stop-on-failure';
                        break;
                    case 'stop-on-incomplete':
                        $args[] = '--stop-on-incomplete';
                        break;
                    case 'stop-on-risky':
                        $args[] = '--stop-on-risky';
                        break;
                    case 'stop-on-skipped':
                        $args[] = '--stop-on-skipped';
                        break;
                    case 'stop-on-warning':
                        $args[] = '--stop-on-warning';
                        break;
                    case 'testdox':
                        $args[] = '--testdox';
                        break;
                    default;
                        $args[] = '--' . $allowed;
                        break;
                }
            }
        }

        return (Arr::isEmpty($args)) ? '' : ' ' . implode(' ', $args);
    }

    /**
     * Run filtered test by function name.
     *
     * @param string $testArg The name of the class.
     * @param array $testSuites An array of test suite paths.
     * @param string $extensions The file extension for PHPUnit test files.
     * @return int A value that indicates success, invalid, or failure.
     */
    public function testByFilter(string $testArg, array $testSuites, string $extension): int {
        if(!self::verifyFilterSyntax($testArg)) {
            Tools::info("Syntax error when filtering.", Logger::DEBUG, Tools::BG_YELLOW);
            return Command::FAILURE;
        }

        [$class, $method] = explode('::', $testArg);
        if(self::testIfSame($class, $testSuites, $extension)) { 
            return Command::FAILURE; 
        }

        foreach($testSuites as $testSuite) {
            $file = $testSuite.$class;
            if(file_exists($file.self::TEST_FILE_EXTENSION)) {
                $filter = "--filter " . escapeshellarg("{$class}::{$method}");
                $this->runTest($filter, self::TEST_COMMAND);
                return Command::SUCCESS;
            }
        }
        
        return Command::FAILURE;
    }
}
```

<br>

## 5. Running Tests <a id="running-test"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

First, you need to create a new command for running tests as demonstrated below:

```bash
php console make:command <your-test-command>
```

The file will be created at `app\Lib\Console\Command`.  Make sure you run composer update if the command is not recognized.

The output is shown below:
```php
<?php
namespace App\Lib\Console\Commands;
 
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Undocumented class
 */
class CustomTestCommand extends Command {
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('my-command');
    }

    /**
     * Executes the command
     *
     * @param InputInterface $input The input.
     * @param OutputInterface $output The output.
     * @return int A value that indicates success, invalid, or failure.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //
    }
}
```

In the `configure` function you will need to set the description, help, and argument for the test name.  For any additional options you will need to define those as well.

You can define the behavior any way you want.  The API above many useful functions for executing tests by name, all tests, and individual suites.

A complete example of the PHPUnit command is shown below.

```php
<?php
namespace Console\Commands;

use Console\Helpers\Testing\PHPUnitRunner;
use Console\Helpers\Testing\TestRunner;
use Console\Helpers\Tools;
use Core\Lib\Logging\Logger;
use Core\Lib\Utilities\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Supports ability to run a phpunit test with only the name of the test file is accepted as a required input.
 * More information can be found <a href="https://chapmancbvcu.github.io/chappy-php-starter/php_unit#running-tests">here</a>.
 */
class RunTestCommand extends Command
{
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('test')
            ->setDescription('Performs the phpunit test.')
            ->setHelp('php console test <test_file_name> without the .php extension.')
            ->addArgument('testname', InputArgument::OPTIONAL, 'Pass the test file\'s name.')

            // Flags
            ->addOption('coverage', null, InputOption::VALUE_NONE, 'Display code coverage summary.')
            ->addOption('debug', null, InputOption::VALUE_NONE, 'Enable debug output.')
            ->addOption('display-depreciations', null, InputOption::VALUE_NONE, 'Show deprecated method warnings.')
            ->addOption('display-errors', null, InputOption::VALUE_NONE, 'Show errors (on by default).')
            ->addOption('display-incomplete', null, InputOption::VALUE_NONE, 'Show incomplete tests in summary .')
            ->addOption('display-skipped', null, InputOption::VALUE_NONE, 'Show skipped tests in summary.')
            ->addOption('fail-on-incomplete', null, InputOption::VALUE_NONE, 'Mark incomplete tests as failed.')
            ->addOption('fail-on-risky', null, InputOption::VALUE_NONE, 'Fail if risky tests are detected.')
            ->addOption('feature', null, InputOption::VALUE_NONE, 'Run feature tests.')
            ->addOption('random-order', null, InputOption::VALUE_NONE, 'Perform tests in random order.')
            ->addOption('reverse-order', null, InputOption::VALUE_NONE, 'Perform tests in reverse order.')
            ->addOption('stop-on-error', null, InputOption::VALUE_NONE, 'Stop on error.')
            ->addOption('stop-on-failure', null, InputOption::VALUE_NONE, 'Stop on first failure.')
            ->addOption('stop-on-incomplete', null, InputOption::VALUE_NONE, 'Stop on incomplete test.')
            ->addOption('stop-on-risky', null, InputOption::VALUE_NONE, 'Stop on risky test.')
            ->addOption('stop-on-skipped', null, InputOption::VALUE_NONE, 'Stop on skipped test.')
            ->addOption('stop-on-warning', null, InputOption::VALUE_NONE, 'Stop on warning.')
            ->addOption('testdox', null, InputOption::VALUE_NONE, 'Use TestDox output.')
            ->addOption('unit', null, InputOption::VALUE_NONE, 'Run unit tests.');
    }
 
    /**
     * Executes the command
     *
     * @param InputInterface $input The input.
     * @param OutputInterface $output The output.
     * @return int A value that indicates success, invalid, or failure.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Get options and arguments
        $testArg = $input->getArgument('testname');
        $unit = $input->getOption('unit');
        $feature = $input->getOption('feature');
        
        $test = new PHPUnitRunner($input, $output);
        $testSuites = [PHPUnitRunner::FEATURE_PATH, PHPUnitRunner::UNIT_PATH];

        // Run all tests.
        if(!$feature && !$unit && !$testArg) {
            return $test->allTests($testSuites, PHPUnitRunner::TEST_FILE_EXTENSION, PHPUnitRunner::TEST_COMMAND);
        }
        
        // Select test based on file name or function name.
        if($testArg && !$unit && !$feature) {
            
            if(Str::contains($testArg, '::')) {
               return $test->testByFilter($testArg, $testSuites, PHPUnitRunner::TEST_FILE_EXTENSION); 
            }
            return $test->selectByTestName($testArg, $testSuites, PHPUnitRunner::TEST_FILE_EXTENSION, PHPUnitRunner::TEST_COMMAND);
        }
        
        /* 
         * Run tests based on --unit and --feature flags and verify successful 
         * completion.
         */
        $runBySuiteStatus = [];
        if(!$testArg && $unit) {
            $runBySuiteStatus[] = $test->testSuite(
                TestRunner::getAllTestsInSuite(PHPUnitRunner::UNIT_PATH, PHPUnitRunner::TEST_FILE_EXTENSION), 
                PHPUnitRunner::TEST_COMMAND
            );
        }
        if(!$testArg && $feature) {
            $runBySuiteStatus[] = $test->testSuite(
                TestRunner::getAllTestsInSuite(PHPUnitRunner::FEATURE_PATH, PHPUnitRunner::TEST_FILE_EXTENSION), 
                PHPUnitRunner::TEST_COMMAND
            );
        }
        if(!$testArg && PHPUnitRunner::testSuiteStatus($runBySuiteStatus)) {
            Tools::info("Completed tests by suite(s)");
            return Command::SUCCESS;
        }

        /* 
         * Run individual test file based on --unit and --feature flags and 
         * verify successful completion.
         */
        $testNameByFlagStatus = [];
        if($testArg && $unit) {
            $testNameByFlagStatus[] = $test->singleFileWithinSuite(
                $testArg, 
                PHPUnitRunner::UNIT_PATH, 
                PHPUnitRunner::TEST_FILE_EXTENSION, 
                PHPUnitRunner::TEST_COMMAND
            );
        }
        if($testArg && $feature) {
            $testNameByFlagStatus[] = $test->singleFileWithinSuite(
                $testArg, 
                PHPUnitRunner::FEATURE_PATH, 
                PHPUnitRunner::TEST_FILE_EXTENSION, 
                PHPUnitRunner::TEST_COMMAND
            );
        }
        if($testArg && PHPUnitRunner::testSuiteStatus($testNameByFlagStatus)) {
            Tools::info("Completed tests by name and suite(s)");
            return Command::SUCCESS;
        }

        Tools::info("There was an issue running unit tests.  Check your command line input.", Logger::ERROR, Tools::BG_RED);
        return Command::FAILURE;
    }
}
```