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
    
}
