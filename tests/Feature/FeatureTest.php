<?php
namespace Tests\Feature;
use Core\DB;
use Core\Session;
use Core\FormHelper;
use App\Models\Users;
use Core\Lib\Utilities\Env;
use Core\Lib\Testing\TestResponse;
use Core\Lib\Testing\ApplicationTestCase;

/**
 * Undocumented class
 */
class FeatureTest extends ApplicationTestCase {
    ///public function test_feature_example_1() {
        // $user = Users::findById(1);
        // dd($user);
        // $name = $user->username;
        // // $output = $this->controllerOutput('admindashboard', 'details', ['1']);

        // $this->assertDatabaseHas('users', [
        //     'email' => $user->email,
        //     'lname' => $user->lname
        // ]);
    // }

    public function test_register_action_creates_user(): void
    {
        // ✅ Mock file upload (required by Uploads::handleUpload)
        $this->mockFile('profileImage');

        // ✅ Prepare valid registration data
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

        // ✅ Perform simulated POST request to /auth/register
        $response = $this->post('/auth/register', $postData);
        
        // ✅ Assert that the user was saved to the database
        $user = \Core\DB::getInstance()->query(
            "SELECT * FROM users WHERE username = ?",
            ['testuser']
        )->first();

        $this->assertNotNull($user, 'User should exist in the database');
        $this->assertEquals('testuser', $user->username);

        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'email' => 'testuser@example.com',
        ]);
    }

    public function test_assert_json_validates_structure_and_values(): void
    {
        $jsonContent = json_encode([
            'status' => 'success',
            'message' => 'User created',
            'id' => 42
        ]);

        $response = new TestResponse($jsonContent, 200);

        // This should pass
        $response->assertJson([
            'status' => 'success',
            'message' => 'User created',
            'id' => 42
        ]);
    }

    public function test_assert_json_fails_on_missing_key(): void
    {
        $this->expectException(\PHPUnit\Framework\ExpectationFailedException::class);
        $this->expectExceptionMessage("Key 'email' not found in JSON response.");

        $jsonContent = json_encode(['username' => 'testuser']);
        $response = new TestResponse($jsonContent, 200);

        // This should fail due to missing 'email' key
        $response->assertJson([
            'email' => 'testuser@example.com'
        ]);
    }

    public function test_assert_json_fails_on_mismatched_value(): void
    {
        $this->expectException(\PHPUnit\Framework\ExpectationFailedException::class);
        $this->expectExceptionMessage("Mismatched value for key 'status' in JSON.");

        $jsonContent = json_encode(['status' => 'error']);
        $response = new TestResponse($jsonContent, 200);

        // This should fail due to incorrect value
        $response->assertJson(['status' => 'success']);
    }

    public function test_assert_json_fails_on_invalid_json(): void
    {
        $this->expectException(\PHPUnit\Framework\ExpectationFailedException::class);
        $this->expectExceptionMessage("Response content is not valid JSON.");

        $response = new TestResponse("not-json", 200);
        $response->assertJson(['key' => 'value']);
    }
}
