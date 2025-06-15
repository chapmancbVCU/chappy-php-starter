<h1 style="font-size: 50px; text-align: center;">Unit Tests</h1>

## Table of contents
1. [Overview](#overview)
2. [Creating Tests](#creating-tests)
3. [Running Tests](#running-tests)
4. [Testing Configuration](#configuration)
5. [Simulating Controller Output](#controller)
6. [ApplicationTestCase Assertions](#test-case-assertions)
    * A. [assertDatabaseHas()](#assert-database-has)
    * B. [assertDatabaseMissing()](#assert-database-missing)
    * C. [assertStatus()](#assert-status)
    * D. [assertJson()](#assert-json)
    * E. [Testing View Variables with `controllerOutput()` and `assertViewContains()`](#view-variables)
    * F. [Simulating GET Requests with `get()` and `TestResponse`](#get)
    * G. [Simulating POST Requests in Feature Tests](#post)
    * H. [Simulating PUT Requests in Feature Tests](#put)
    * I. [Mocking File Uploads in Tests](#mock-files)
7. [PHPUnit Assertions](#phpunit-assertions)
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The Unit Test system in the Chappy PHP framework enables developers to write automated tests for their applications using a clean, expressive API layered on top of PHPUnit. It includes a custom ApplicationTestCase base class that simulates HTTP requests (get, post, and put) and provides convenient methods for asserting database state, capturing controller output, and validating view data.

This setup is ideal for:
- Testing controller logic in isolation
- Verifying database insertions or updates
- Ensuring CSRF protection works as expected
- Simulating file uploads or form submissions
- Confirming correct redirects and rendered content

The test layer integrates tightly with your MVC routing system, allowing you to call routes directly using URI patterns like /auth/register, just as a real user would in the browser.

All tests run in an isolated environment with support for:
- In-memory SQLite or custom test databases
- Auto-migration and seeding via your console commands
- Custom response wrappers for fluent test assertions

🧪 Whether you're testing form validation, user registration, or database interactions, the Chappy framework's testing system provides the power and flexibility to help ensure your application is reliable and maintainable.

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

<br>

### A. 🔍 `assertDatabaseHas()` <a id="assert-database-has"></a>
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

### B. 🚫 `assertDatabaseMissing()` <a id="assert-database-missing"></a>
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

### C. `assertStatus(int $expected)`  <a id="assert-status"></a>
**Overview**

The `assertStatus()` method is used in feature and unit tests to verify that a controller or simulated HTTP request returned the expected status code. This method is part of the `TestResponse` class and ensures your controller logic responds with the correct HTTP semantics (e.g., 200 OK, 404 Not Found, 500 Internal Server Error).

**Signature**
```php
public function assertStatus(int $expected): void
```

**Parameters**

| Parameter   | Type  | Description                                                                        |
| ----------- | ----- | ---------------------------------------------------------------------------------- |
| `$expected` | `int` | The HTTP status code you expect the response to return (e.g., `200`, `404`, `500`) |


**Usage**
Use this method after performing a simulated request using `get()`, `post()`, or `put()` in your `ApplicationTestCase`. It will throw an assertion error if the actual status code does not match the expected one.


**Example**
```php
public function test_homepage_returns_ok_status(): void
{
    $response = $this->get('/');

    $response->assertStatus(200);
}
```

If the response status is not `200`, the test will fail with a message like:
```lua
Expected response status 200 but got 404.
```

**When to Use**
- After simulating a request to validate that your route and controller handled it successfully.
- To confirm that error routes return correct HTTP codes like `404`, `403`, or `500`.

<br>

### D. ✅ assertJson()<a id="view-variables"></a>
**Overview**
The `assertJson()` method allows you to verify that the HTTP response body is valid JSON and contains specific key-value pairs. This is useful when testing API endpoints or any controller that returns JSON responses, such as AJAX routes or RESTful APIs.

It ensures:
- The response content is valid JSON
- All expected keys are present
- All expected values match exactly (using `assertSame`)

**Method Signature**
```php
public function assertJson(array $expected): void
```

**Parameters**

| Parameter   | Type    | Description                                                           |
| ----------- | ------- | --------------------------------------------------------------------- |
| `$expected` | `array` | An associative array of key-value pairs expected in the JSON response |

**How It Works**
- Parses JSON: It attempts to json_decode the response content.
- Validates Format: It asserts the content is a valid JSON array.
- Checks Keys: Asserts that each key in the $expected array exists.
- Checks Values: Ensures the actual value matches the expected value exactly.

**Example Usage**
✅ Successful Test
```php
$response = new TestResponse(json_encode([
    'status' => 'success',
    'message' => 'User created',
    'id' => 42
]));

$response->assertJson([
    'status' => 'success',
    'message' => 'User created',
    'id' => 42
]);
```

❌ Failing Test (Missing Key)
```php
$response = new TestResponse(json_encode([
    'username' => 'testuser'
]));

$response->assertJson([
    'email' => 'testuser@example.com' // will fail: key not found
]);
```

❌ Failing Test (Mismatched Value)
```php
$response = new TestResponse(json_encode([
    'status' => 'error'
]));

$response->assertJson([
    'status' => 'success' // will fail: mismatched value
]);
```

**When to Use**
- API endpoint response validation
- AJAX controller return assertions
- JSON-based form submission confirmation

### E. 🧪 Testing View Variables with `controllerOutput()` and `assertViewContains()` <a id="view-variables"></a>

This section describes how to test whether a controller assigns the expected properties to the `View` object using `controllerOutput()` and the `assertViewContains()` assertion method.

✅ Overview
In this framework, controller actions assign data to views via dynamic properties:

```php
$this->view->user = $user;
$this->view->render('admindashboard.details');
```

To test whether specific view variables are set correctly during a controller action, you can use:
- `controllerOutput()` – simulates dispatching a controller and stores output
- `logViewForTesting()` – captures the view during rendering
- `assertViewContains()` – checks whether a specific view property exists (and optionally, its value)

🧩 Prerequisites
Make sure the controller action includes:
```php
$this->logViewForTesting($this->view);
$this->view->render('admindashboard.details');
```

📥 Example Controller Action
```php
public function detailsAction($id): void {
    $user = Users::findById((int)$id);
    $profileImage = ProfileImages::findCurrentProfileImage($user->id);

    $this->view->profileImage = $profileImage;
    $this->view->user = $user;

    $this->logViewForTesting($this->view); // Required for testing
    $this->view->render('admindashboard.details');
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

### F. 📥 Simulating GET Requests with `get()` and `TestResponse` <a id="get"></a>

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

### G. ✅ Simulating POST Requests in Feature Tests <a id="post"></a>

In your framework's test suite, the `post()` method allows you to simulate POST requests to any controller action as if it were triggered via a browser form submission. This is especially useful for testing routes like `/auth/register` or `/products/create`.

**Example: Register User Test**

```php
public function test_register_action_creates_user(): void
{
    // 👤 Mock file upload (required even if no image uploaded)
    $this->mockFile('profileImage');

    // 🧪 Prepare valid form input with CSRF token
    $postData = [
        'fname' => 'Test',
        'lname' => 'User',
        'email' => 'testuser@example.com',
        'username' => 'testuser',
        'description' => 'Test description',
        'password' => 'Password@123',
        'confirm' => 'Password@123',
        'csrf_token' => FormHelper::generateToken(),
    ];

    // 🚀 Perform request to controller
    $response = $this->post('/auth/register', $postData);

    // ✅ Assert user was created
    $user = \Core\DB::getInstance()->query(
        "SELECT * FROM users WHERE username = ?",
        ['testuser']
    )->first();

    $this->assertNotNull($user, 'User should exist in the database');
    $this->assertEquals('testuser', $user->username);

    // 🔒 Also confirm with database helper
    $this->assertDatabaseHas('users', [
        'username' => 'testuser',
        'email' => 'testuser@example.com',
    ]);
}
```

<br>

### H. ♻️ Simulating PUT Requests in Feature Tests <a id="put">
The `put()` method in `ApplicationTestCase` allows you to simulate HTTP `PUT` requests to test controller actions that update records. It mimics a real browser form submission using the `PUT` method and passes data to the targeted controller action.

This is especially useful for testing resourceful routes like `/users/update/1`, where form data is submitted via `PUT`.

🧪 Example: Update User Test
```php
public function test_put_updates_user(): void
{
    // ✅ Seed user
    DB::getInstance()->insert('users', [
        'fname' => 'Original',
        'lname' => 'User',
        'email' => 'original@example.com',
        'username' => 'originaluser',
        'description' => 'Seeded user',
        'password' => password_hash('Password@123', PASSWORD_DEFAULT),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $userId = DB::getInstance()->lastID();

    // ✅ Prepare updated values
    $data = [
        'username' => 'updateduser',
        'email' => 'updated@example.com',
        'csrf_token' => FormHelper::generateToken()
    ];

    // ✅ Simulate PUT request to update controller
    $response = $this->put("/admindashboard/update/{$userId}", $data);

    // ✅ Assert changes in DB
    $user = DB::getInstance()->query("SELECT * FROM users WHERE id = ?", [$userId])->first();

    $this->assertNotNull($user);
    $this->assertEquals('updateduser', $user->username);
    $this->assertEquals('updated@example.com', $user->email);
}
```

🔐 CSRF Support
Ensure you include a valid CSRF token using your form helper before submitting the `PUT` request:
```php
'csrf_token' => FormHelper::generateToken()
```

✅ When to Use
Use put() in feature tests to:
- Verify that a controller correctly updates a database record
- Simulate a PUT or PATCH form submission from the browser
- Test CSRF validation and data sanitization
- Assert redirect behavior after update

<br>

### I. 📂 Mocking File Uploads in Tests <a id="mock-files"></a>

When your controller expects file uploads (like $_FILES['profileImage']), you must mock this data in your test to avoid runtime errors.

**Usage in a test**

```php
$this->mockFile('profileImage');
```

This simulates an empty file upload, which is sufficient for passing validation or skipping optional image logic in `Uploads::handleUpload()`.

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
