<h1 style="font-size: 50px; text-align: center;">Unit Tests</h1>

## Table of contents
1. [Overview](#overview)
2. [Simulating Controller Output](#controller)
3. [ApplicationTestCase Assertions](#test-case-assertions)
    * A. [assertDatabaseHas()](#assert-database-has)
    * B. [assertDatabaseMissing()](#assert-database-missing)
    * C. [assertStatus()](#assert-status)
    * D. [assertJson()](#assert-json)
    * E. [Testing View Variables with `controllerOutput()` and `assertViewContains()`](#view-variables)
4. [Simulating DELETE Requests with `delete()`](#delete)
5. [Simulating GET Requests with `get()`](#get)
    * A. [`get()` Standard Page Load Test](#get-standard)
    * B. [`get()` With API Example](#get-api)
6. [Simulating PATCH Requests with `patch()`](#patch)
7. [Simulating POST Requests with `post()`](#post)
    * A. [`post()` Register User Test](#post-standard)
    * B. [`post()` with API Example](#post-api)
8. [Simulating PUT Requests with `put()`](#put)
9. [Mocking File Uploads in Tests](#mock-files)
10. [Additional Functions](#additional-functions)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

The Unit Test system in the Chappy PHP framework enables developers to write automated tests for their applications using a clean, expressive API layered on top of PHPUnit and Vitest. It includes a custom ApplicationTestCase base class that simulates HTTP requests (get, post, put, patch, and delete) and provides convenient methods for asserting database state, capturing controller output, and validating view data.

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

Guides for running PHPUnit and Vitest test from the console can be found in their respective sections.

<br>

## 2. Simulating Controller Output <a id="controller"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
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

## 3. ApplicationTestCase Assertions <a id="test-case-assertions"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
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

## 4 Simulating DELETE Requests with `delete() <a id="delete"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `ApplicationTestCase` class provides a Laravel-style `delete()` helper that lets you simulate HTTP GET requests in your feature tests. This function parses a URI string into a controller, action, and optional parameters, then returns a `TestResponse` object for assertion.

**Example destroy action for an API**
```php
<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Controllers\FavoritesController;
use Core\DB;
use Core\FormHelper;
use Core\Lib\Http\JsonResponse;
use Core\Lib\Testing\ApplicationTestCase;
use Core\Lib\Utilities\Env;

class RESTfulDestroyTest extends ApplicationTestCase
{
    public function test_destroy_deletes_one_favorite_and_leaves_other_intact(): void
    {
        self::enableJsonTestingMode();
        self::ensureSessionStarts();

        // 1) Seed user
        DB::getInstance()->insert('users', [
            'fname'       => 'Test',
            'lname'       => 'User',
            'email'       => 'destroy@example.com',
            'username'    => 'destroyuser',
            'description' => 'Seeded user',
            'password'    => password_hash('Password@123', PASSWORD_DEFAULT),
            'deleted'     => 0,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        $userId = (int) DB::getInstance()->lastID();
        $_SESSION[Env::get('CURRENT_USER_SESSION_NAME')] = $userId;

        // 2) Seed TWO favorites
        DB::getInstance()->insert('favorites', [
            'user_id'    => $userId,
            'name'       => 'Norfolk, VA',
            'latitude'   => 36.85,    // you said you store 2 decimal places
            'longitude'  => -76.29,
            'is_home'    => 0,
            'deleted'    => 0,
        ]);
        $fav1Id = (int) DB::getInstance()->lastID();

        DB::getInstance()->insert('favorites', [
            'user_id'    => $userId,
            'name'       => 'Virginia Beach, VA',
            'latitude'   => 36.85,
            'longitude'  => -75.98,
            'is_home'    => 0,
            'deleted'    => 0,
        ]);
        $fav2Id = (int) DB::getInstance()->lastID();

        $this->assertNotSame($fav1Id, $fav2Id);

        // 3) JSON body for CSRF (destroyAction reads csrf_token from JsonResponse::get())
        $payload = [
            'csrf_token' => FormHelper::generateToken(),
        ];
        FavoritesController::setRawInputOverride($payload);

        // 4) DELETE /favorites/destroy/{id}
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $response = $this->delete("/favorites/destroy/{$fav1Id}", []);

        // Cleanup override
        FavoritesController::setRawInputOverride();

        // 5) On success, destroyAction returns no body
        $response->assertStatus(200);
        $this->assertSame('', trim($response->getContent()), 'Expected empty response body on success.');

        // 6) Assert only ONE favorite was deleted
        // Soft delete (preferred): deleted = 1
        $deletedRow = DB::getInstance()->query("SELECT * FROM favorites WHERE id = ?", [$fav1Id])->first();
        $remainingRow = DB::getInstance()->query("SELECT * FROM favorites WHERE id = ?", [$fav2Id])->first();

        // If you hard delete, $deletedRow will be false/null.
        // If you soft delete, it will exist and have deleted=1.
        if ($deletedRow) {
            $this->assertSame(1, (int)($deletedRow->deleted ?? 0), 'Expected destroyed favorite to be soft-deleted.');
        } else {
            $this->assertFalse($deletedRow, 'Expected destroyed favorite to be removed.');
        }

        $this->assertNotNull($remainingRow, 'Expected second favorite to remain.');
        if ($remainingRow) {
            $this->assertSame(0, (int)($remainingRow->deleted ?? 0), 'Expected remaining favorite to not be deleted.');
            $this->assertSame('Virginia Beach, VA', (string)$remainingRow->name);
        }
    }
}
```

<br>

## 5. Simulating GET Requests with `get() <a id="get"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `ApplicationTestCase` class provides a Laravel-style `get()` helper that lets you simulate HTTP GET requests in your feature tests. This function parses a URI string into a controller, action, and optional parameters, then returns a `TestResponse` object for assertion.

🔧 Syntax
```php
$this->get(string $uri): TestResponse
```

<br>

### A. `get()` Standard Page Load Test <a id="get-standard"></a>
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

<br>

### A. `get()` With API Example <a id="get-api"></a>
The example below demonstrates usage when building an API:
```php
<?php
declare(strict_types=1);
namespace Tests\Feature;

use Core\DB;
use Core\Lib\Utilities\Env;
use Core\Lib\Http\JsonResponse; // trait with static test mode toggles
use Core\Lib\Testing\ApplicationTestCase;

/**
 * Unit tests
 */
class RESTfulShowTest extends ApplicationTestCase {
    public function test_show_returns_favorites_for_current_user(): void
    {
        // Ensure JSON responses don't exit during tests
        self::enableJsonTestingMode();

        // Start session for AuthService::currentUser()
        self::ensureSessionStarts();

        // 1) Seed a user record
        DB::getInstance()->insert('users', [
            'fname'       => 'Test',
            'lname'       => 'User',
            'email'       => 'test@example.com',
            'username'    => 'testuser',
            'description' => 'PHPUnit seeded user',
            'password'    => password_hash('Password@123', PASSWORD_DEFAULT),

            // Include fields your model/framework expects
            'deleted'     => 0,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        $userId = (int) DB::getInstance()->lastID();
        $this->assertGreaterThan(0, $userId);

        // 2) "Log in" as that user (adjust this key to your AuthService/session convention)
        $sessionKey = Env::get('CURRENT_USER_SESSION_NAME');
        $_SESSION[$sessionKey] = $userId;

        // 3) Seed favorites for that user
        DB::getInstance()->insert('favorites', [
            'user_id'     => $userId,
            'name'        => 'Norfolk, VA',
            'latitude'    => 36.8508,
            'longitude'   => -76.2859,
            'is_home'     => 0,
            'deleted'     => 0,
        ]);

        DB::getInstance()->insert('favorites', [
            'user_id'     => $userId,
            'name'        => 'Virginia Beach, VA',
            'latitude'    => 36.8529,
            'longitude'   => -75.9780,
            'is_home'     => 1,
            'deleted'     => 0
        ]);

        // 4) Call the controller endpoint (router-style URL)
        // If your ApplicationTestCase::get() routes /controller/action, this should hit FavoritesController::showAction()
        $response = $this->get('/favorites/show');

        // 5) Assert response status + JSON payload
        $response->assertStatus(200);

        $payload = json_decode($response->getContent(), true);

        $this->assertIsArray($payload, 'Response body is not valid JSON.');
        $this->assertTrue($payload['success'] ?? false, 'Expected success=true in JSON response.');
        $this->assertArrayHasKey('data', $payload, 'Expected data key in JSON response.');
        $this->assertIsArray($payload['data'], 'Expected data to be an array.');

        // 6) Assert only this user’s favorites returned
        $this->assertCount(2, $payload['data']);

        foreach ($payload['data'] as $fav) {
            $this->assertSame($userId, (int)($fav['user_id'] ?? 0));
        }
    }
}
```

<br>

🔍 Notes
- `get()` uses `controllerOutput()` internally to resolve the controller and action.
- Extra URI segments beyond `/controller/action` are passed as method parameters.
- If the controller or action is not found, a 404 response is returned with the error message.

<br>

## 6. Simulating PATCH Requests with `patch() <a id="patch"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `ApplicationTestCase` class provides a Laravel-style `patch()` helper that lets you simulate HTTP PATCH requests in your feature tests. This function parses a URI string into a controller, action, and optional parameters, then returns a `TestResponse` object for assertion.

**Example PATCH request for an API**
```php
<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Controllers\FavoritesController;
use Core\DB;
use Core\Lib\Utilities\Env;
use Core\Lib\Http\JsonResponse; // trait with static test mode toggles
use Core\Lib\Testing\ApplicationTestCase;
use Core\FormHelper;

/**
 * Unit tests
 */
class RESTfulPatchTest extends ApplicationTestCase {
    public function test_patch_sets_selected_favorite_as_home_and_unsets_previous_home(): void
    {
        // Ensure JSON responses don't exit during tests
        self::enableJsonTestingMode();

        self::ensureSessionStarts();

        // 1) Seed user
        DB::getInstance()->insert('users', [
            'fname'       => 'Test',
            'lname'       => 'User',
            'email'       => 'test@example.com',
            'username'    => 'testuser',
            'description' => 'Seeded user',
            'password'    => password_hash('Password@123', PASSWORD_DEFAULT),
            'deleted'     => 0,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        $userId = (int) DB::getInstance()->lastID();
        $this->assertGreaterThan(0, $userId);

        // 2) Authenticate session (match what your router/AuthService expects)
        $_SESSION[Env::get('CURRENT_USER_SESSION_NAME')] = $userId;

        // 3) Seed two favorites: one currently home, one not
        DB::getInstance()->insert('favorites', [
            'user_id'     => $userId,
            'name'        => 'Norfolk, VA',
            'latitude'    => 36.8508,
            'longitude'   => -76.2859,
            'is_home'     => 1,
            'deleted'     => 0
        ]);
        $currentHomeId = (int) DB::getInstance()->lastID();

        DB::getInstance()->insert('favorites', [
            'user_id'     => $userId,
            'name'        => 'Virginia Beach, VA',
            'latitude'    => 36.8529,
            'longitude'   => -75.9780,
            'is_home'     => 0,
            'deleted'     => 0
        ]);
        $targetId = (int) DB::getInstance()->lastID();

        $this->assertNotSame($currentHomeId, $targetId);

        // 4) Prepare JSON body (apiCsrfCheck reads csrf_token from JsonResponse::get())
        $payload = [
            'csrf_token' => FormHelper::generateToken(),
        ];

        // IMPORTANT: set the override on the class using the trait
        FavoritesController::setRawInputOverride($payload);

        // If you added this testing flag to prevent exit()
        FavoritesController::$testing = true;

        $_SERVER['REQUEST_METHOD'] = 'PATCH';

        $response = $this->patch("/favorites/patch/{$targetId}", []);

        FavoritesController::setRawInputOverride();
        // 6) Assert response looks like JSON success (optional but helpful)
        $response->assertStatus(200);

        $this->assertSame('', trim($response->getContent()), 'Expected empty response body on success.');

        // 7) Assert DB: previous home unset, target set
        $old = DB::getInstance()->query("SELECT is_home FROM favorites WHERE id = ?", [$currentHomeId])->first();
        $new = DB::getInstance()->query("SELECT is_home FROM favorites WHERE id = ?", [$targetId])->first();

        $this->assertNotNull($old);
        $this->assertNotNull($new);

        $this->assertSame(0, (int) $old->is_home, 'Expected previous home to be unset.');
        $this->assertSame(1, (int) $new->is_home, 'Expected selected favorite to be set as home.');

        // Cleanup override so it doesn't leak into other tests
        JsonResponse::setRawInputOverride();
    }
}
```

<br>

## 7. Simulating POST Requests with `post()` <a id="post"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
In your framework's test suite, the `post()` method allows you to simulate POST requests to any controller action as if it were triggered via a browser form submission. This is especially useful for testing routes like `/auth/register` or `/products/create`.

<br>

### A. `post()` Register User Test <a id="post-standard"></a>
Below is an example using standard create operation.

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

### B. `post()` with API Example <a id="post-api"></a>
The example below demonstrates a `PUT` request whe building an API:
```php
<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Controllers\FavoritesController;
use Core\DB;
use Core\Lib\Utilities\Env;
use Core\Lib\Http\JsonResponse; // trait with static test mode toggles
use Core\Lib\Testing\ApplicationTestCase;
use Core\FormHelper;

/**
 * Unit tests
 */
class RESTfulStoreTest extends ApplicationTestCase {
    public function test_store_creates_favorite_for_current_user(): void
    {
        // Prevent jsonResponse() from exiting during tests
        self::enableJsonTestingMode();

        self::ensureSessionStarts();

        // 1) Seed user
        DB::getInstance()->insert('users', [
            'fname'       => 'Test',
            'lname'       => 'User',
            'email'       => 'test@example.com',
            'username'    => 'testuser',
            'description' => 'Seeded user',
            'password'    => password_hash('Password@123', PASSWORD_DEFAULT),
            'deleted'     => 0,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        $userId = (int) DB::getInstance()->lastID();
        $this->assertGreaterThan(0, $userId);

        // 2) Authenticate session (whatever AuthService::currentUser() expects)
        $_SESSION[Env::get('CURRENT_USER_SESSION_NAME')] = $userId;

        // 3) Prepare JSON payload (storeAction reads JSON body via JsonResponse::get())
        $payload = [
            'name'       => 'Norfolk, VA',
            'latitude'   => '36.85',
            'longitude'  => '-76.28',
            'csrf_token' => FormHelper::generateToken(),
        ];

        // Inject JSON body for JsonResponse::get()
        FavoritesController::setRawInputOverride($payload);

        // 4) Call store endpoint (your controller/action routing)
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $response = $this->post('/favorites/store', []);

        // Cleanup override to avoid leaking into other tests
        FavoritesController::setRawInputOverride();

        // 5) On success, your action returns no JSON body (same as patch)
        $response->assertStatus(200);
        $this->assertSame('', trim($response->getContent()), 'Expected empty response body on success.');

        // 6) Assert DB row created for this user
        $row = DB::getInstance()->query(
            "SELECT * FROM favorites WHERE user_id = ? AND name = ?",
            [$userId, 'Norfolk, VA']
        )->first();

        $this->assertNotNull($row, 'Expected favorites row to be created.');
        $this->assertSame($userId, (int)$row->user_id);
        $this->assertSame('Norfolk, VA', (string)$row->name);

        // Float comparisons can be annoying; cast to float and compare with delta if needed
        $this->assertEquals(36.85, (float)$row->latitude);
        $this->assertEquals(-76.28, (float)$row->longitude);

        // Optional if you use soft deletes
        if (property_exists($row, 'deleted')) {
            $this->assertSame(0, (int)$row->deleted);
        }
    }
}
```

<br>

## 8. Simulating PUT Requests with `put()` <a id="put"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `put()` method in `ApplicationTestCase` allows you to simulate HTTP `PUT` requests to test controller actions that update records. It mimics a real browser form submission using the `PUT` method and passes data to the targeted controller action.

This is especially useful for testing resourceful routes like `/users/update/1`, where form data is submitted via `PUT`.

🧪 Example: Update User Test
```php
public function test_put_updates_user(): void
{
    // ✅ Seed user
    UserFactory::factory(UserFactory::class)->createOne([
        'fname' => 'Original',
        'lname' => 'User',
        'email' => 'original@example.com',
        'username' => 'originaluser',
        'description' => 'Seeded user',
    ]);

    $userId = DB::getInstance()->lastID();

    // ✅ Prepare updated values
    $data = [
        'username' => 'updateduser',
        'email' => 'updated@example.com',
        'images_sorted' => '[]',
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

## 9. 📂 Mocking File Uploads in Tests <a id="mock-files"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
When your controller expects file uploads (like $_FILES['profileImage']), you must mock this data in your test to avoid runtime errors.

**Usage in a test**

```php
$this->mockFile('profileImage');
```

This simulates an empty file upload, which is sufficient for passing validation or skipping optional image logic in `Uploads::handleUpload()`.

<br>

## 10. Additional Functions <a id="additional-functions"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

**enableJsonTestingMode**

Set JsonResponse::$testing to true so we can test responses.

<br>

**ensureSessionStarts**

Simulate a session where a function we are testing expects a user  to be authenticated.