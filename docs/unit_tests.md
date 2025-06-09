<h1 style="font-size: 50px; text-align: center;">Unit Tests</h1>

## Table of contents
1. [Overview](#overview)
2. [Creating Tests](#creating-tests)
3. [Running Tests](#running-tests)
4. [Testing Configuration](#configuration)
5. [Simulating Controller Output](#controller)
6. [ApplicationTestCase Assertions](#test-case-assertions)
7. [PHPUnit Assertions](#phpunit-assertions)
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

This version of the PHPUnit test class adds support for migrations and database seeding.

**Naming Functions**
A PHPUnit enforced rule requires that each test case within your class begins with the word `test`.

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

**Running Tests With PHPUnit**

You can use `vendor/bin/phpunit` to bypass the console's `test` command.

**Supported PHPUnit flags**

The following flags are supported without running PHPUnit directly using `vendor/bin/phpunit`.
🧹 Coverage and Logging

| Flag                       | Description                             |
| -------------------------- | --------------------------------------- |
| `--coverage-text`          | Output code coverage summary to console |

✅ Output / Display Flags

| Flag                     | Description                                                      |
| ------------------------ | ---------------------------------------------------------------- |
<!-- | `--colors=always`        | Always use ANSI colors in output (`auto`, `never`, `always`)     | -->
| `--debug`                | Show debugging info for each test (e.g., method names being run) |
| `--display-deprecations` | Show deprecated method warnings                                  |
| `--display-errors`       | Show errors (on by default)                                      |
| `--display-incomplete`   | Show incomplete tests in summary                                 |
| `--display-skipped`      | Show skipped tests in summary                                    |
| `--fail-on-incomplete`   | Mark incomplete tests as failed                                  |
| `--fail-on-risky`        | Fail if risky tests are detected                                 |
| `--testdox`              | Print readable test names (e.g., "It returns true on success")   |


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

**Run A Test Suite**

Run all test within a particular test suite by adding the `--unit` and/or `--feature` flags.

**Run Specific Test File Within A Suite**

You can run all test within a specific test file for an individual suite by specifying the file name and adding the `--unit` and/or `--feature` flags.

<br>

## 4. Testing Configuration <a id="configuration"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

The Chappy.php framework allows you to run your unit and feature tests against **SQLite (in-memory)** or **MySQL**, depending on your project's requirements.

This gives you flexibility for:
- ✅ Fast, isolated testing with SQLite
- ✅ Full-database compatibility testing with MySQL

### 🧪 SQLite (In-Memory) for Fast Testing

For quick and isolated test runs, SQLite is ideal. It requires **no setup** and runs entirely in memory.

#### **Configure PHPUnit to use SQLite**
Update your `phpunit.xml` and make sure `.env.testing` does not exist in your project root:

```xml
<env name="APP_ENV" value="testing"/>
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
...

To toggle on or off refresh, migrations, and seeding uncomment out the following:

```xml
...
<!-- Feature test configuration -->
<!-- <env name="DB_REFRESH" value="true"/> -->
<!-- <env name="DB_MIGRATE" value="true"/> -->
<!-- <env name="DB_SEED" value="true"/> -->
```

Benefits
- No external service needed
- Fastest test execution
- Great for CI pipelines

🐬 MySQL for Real-World Compatibility
To test real-world behavior like foreign key constraints or strict SQL modes (ONLY_FULL_GROUP_BY), use MySQL.

Configure PHPUnit to use MySQL by entering the required information in the MySQL/MariaDB section.  Leave the SQLite information commented out.
```xml
<php>
    <env name="APP_ENV" value="testing"/>
    <!-- In memory SQLite config -->
    <!-- <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/> -->

    <!-- MySQL/MariaDB test DB config -->
    <env name="DB_CONNECTION" value="mysql_testing"/>
    <env name="DB_HOST" value="127.0.0.1"/>
    <env name="DB_PORT" value="3306"/>
    <env name="DB_DATABASE" value=""/>
    <env name="DB_USERNAME" value=""/>
    <env name="DB_PASSWORD" value=""/>

    <!-- Feature test configuration -->
    <!-- <env name="DB_REFRESH" value="true"/> -->
    <!-- <env name="DB_MIGRATE" value="true"/> -->
    <!-- <env name="DB_SEED" value="true"/> -->
</php>
```

You can keep everything commented out except for the `APP_ENV` line and make a copy of the `.env.testing.sample` file and rename it to `.env.testing` and configure that file.

```
DB_CONNECTION=mysql_testing
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
DB_REFRESH=true
DB_MIGRATE=true
DB_SEED=true
```

✅ Requirements
- MySQL test database must exist (e.g., chappy_test)
- Test user must have privileges to create/drop tables

<br>

## 5. Simulating Controller Output <a id="controller"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The Chappy.php framework provides a convenient test helper method named `controllerOutput()` that allows you to simulate controller behavior inside unit or feature tests, similar to how routes behave when accessed via a browser.

This method is available within `ApplicationTestCase` and is ideal for verifying the output of controller actions.

### 🔧 Syntax

```php
$this->controllerOutput(string $controller, string $action, array $params = []): string
```

Description of arguments:
- controller — The lowercase controller slug (e.g. `'home'`, `'user'`)
- action — The lowercase action name without the `Action` suffix (e.g. `'index'`, `'details'`)
- params — Optional array of route parameters (like path segments)

🧪 Example Usage
✅ Simulate a basic route like `/home/index`
```php
$html = $this->controllerOutput('home', 'index');
$this->assertStringContainsString('Welcome to Chappy.php', $html);
```

✅ Simulate a route like `/admindashboard/details/1`
```php
public function test_feature_example_1() {
    $user = Users::findById(1);

    $username = $user->username;
    $output = $this->controllerOutput('admindashboard', 'details', ['1']);

    $this->assertStringContainsString('Details for '.$username, $output);
}
```

✅ Behavior Details
- The controller is instantiated using the same logic as the router (`App\Controllers\{Name}Controller`)
- The action is called with any extra parameters (mimicking a parsed URL like `/user/edit/3`)
- Output from the controller is captured using `ob_start()` and returned as a string
- This method is ideal for asserting against full HTML responses or checking content rendered by views

⚠️ Note on Test Data
Since this method operates inside your test environment, ensure the required database records (e.g. users, posts) exist either by:
- Calling a seeder (e.g. `DatabaseSeeder`)
- Manually inserting records
- Otherwise, calls like `Users::findById($id)` may return `null`, causing your view or controller to throw exceptions during the test.

<br>

## 6. ApplicationTestCase Assertions <a id="test-case-assertions"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework's test infrastructure provides convenient assertion helpers to validate the state of the database during tests. These assertions are especially useful in integration and feature tests that interact with the database.

The following support assertions are available in the ApplicationTestCase base class:

🔍 `assertDatabaseHas()`
```php
$this->assertDatabaseHas(string $table, array $data, string $message = '');
```

Description:
Asserts that a given row exists in the specified database table. This is useful for verifying that a model or query correctly created or updated a record.

Parameters:

| Name | Type | Description |
|:----:|:----:|-------------|
| $table | string | The name of the database table to search. |
| $data | array | Key-value pairs representing column and expected value. |
| $message | string | (Optional) Custom error message if the assertion fails. |

Example:
```php
$this->assertDatabaseHas('users', [
    'email' => 'jane@example.com',
    'lname' => 'Doe',
]);
```

If the record is not found, the test will fail and display an informative error message.

<br>

🚫 `assertDatabaseMissing()`
```php
$this->assertDatabaseMissing(string $table, array $data, string $message = '');
```

Description:
Asserts that no row exists in the given table with the provided data. Useful for checking deletions, failed inserts, or rollback behavior.

Parameters:

| Name | Type | Description |
|:----:|:----:|-------------|
| $table | string | The name of the database table to search. |
| $data | array | Key-value pairs representing column and value to search for. |
| $message | string | (Optional) Custom error message if the assertion fails. |

Example:
```php
$this->assertDatabaseMissing('orders', [
    'user_id' => 1,
    'status' => 'canceled',
]);
```

This will fail if a record with the specified conditions exists in the table.

<br>

🧪 Testing View Variables with c`ontrollerOutput()` and `assertViewContains()`
This section describes how to test whether a controller assigns the expected properties to the `View` object using `controllerOutput()` and the `assertViewContains()` assertion method.

✅ Overview
In this framework, controller actions assign data to views via dynamic properties:

```php
$this->view->user = $user;
$this->view->render('admindashboard/details');
```

To test whether specific view variables are set correctly during a controller action, you can use:
- `controllerOutput()` – simulates dispatching a controller and stores output
- `logViewForTesting()` – captures the view during rendering
- `assertViewContains()` – checks whether a specific view property exists (and optionally, its value)

🧩 Prerequisites
Make sure the controller action includes:
```php
$this->logViewForTesting($this->view);
$this->view->render('admindashboard/details');
```

📥 Example Controller Action
```php
public function detailsAction($id): void {
    $user = Users::findById((int)$id);
    $profileImage = ProfileImages::findCurrentProfileImage($user->id);

    $this->view->profileImage = $profileImage;
    $this->view->user = $user;

    $this->logViewForTesting($this->view); // Required for testing
    $this->view->render('admindashboard/details');
}
```

🧪 Writing the Test
```php
public function test_user_details_view_contains_user()
{
    static::controllerOutput('admindashboard', 'details', [1]);

    $this->assertViewContains('user');
    $this->assertViewContains('profileImage');
}
```

You can also verify the value if needed:
```php
$this->assertViewContains('user', Users::findById(1));
```

🧠 How It Works
- `controllerOutput()` simulates the route to `AdmDashboardController@details`
- The controller calls `logViewForTesting()`, which stores `$this->view` in a static test property
- `assertViewContains()` retrieves the view object and verifies its dynamic properties

<br>

📥 Simulating GET Requests with `get()` and `TestResponse`
The `ApplicationTestCase` class provides a Laravel-style `get()` helper that lets you simulate HTTP GET requests in your feature tests. This function parses a URI string into a controller, action, and optional parameters, then returns a `TestResponse` object for assertion.

🔧 Syntax
```php
$this->get(string $uri): TestResponse
```

✅ Example
```php
public function test_homepage_loads_successfully(): void
{
    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('Welcome');
}
```

This simulates a route to `HomeController@indexAction()` and captures its output and response code.

⚙️ Behavior
- Automatically maps URIs like `/products/show/3` to `ProductsController::showAction(3)`
- Returns a TestResponse object with:
    - `assertStatus(int $expected)`
    - `assertSee(string $text)`
    - `getContent(): string`

📦 TestResponse Class

The `TestResponse` class is used to encapsulate the response content and status returned by `get()`. It provides useful assertion helpers for verifying behavior in your feature tests.

✅ Methods

| Method                 | Description                                       |
| ---------------------- | ------------------------------------------------- |
| `assertStatus(int)`    | Asserts the response returned the expected status |
| `assertSee(string)`    | Asserts that the response content contains text   |
| `getContent(): string` | Returns the raw content captured from the output  |


📘 Example
```php
$response = $this->get('/user/profile');
$response->assertStatus(200);
$response->assertSee('Profile');
```

🔍 Notes
- `get()` uses `controllerOutput()` internally to resolve the controller and action.
- Extra URI segments beyond `/controller/action` are passed as method parameters.
- If the controller or action is not found, a 404 response is returned with the error message.

<br>

## 7. PHPUnit Assertions <a id="phpunit-assertions"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
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
