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
    // public function test_feature_example_1() {
    //     $user = Users::findById(1);
    //     dd($user);
    //     $name = $user->username;
    //     // $output = $this->controllerOutput('admindashboard', 'details', ['1']);

    //     $this->assertDatabaseHas('users', [
    //         'email' => $user->email,
    //         'lname' => $user->lname
    //     ]);
    // }

    public function test_assert_status_matches_expected()
    {
        // Arrange: Simulate a response with HTTP 200
        $response = new TestResponse('Success content', 200);

        // Act & Assert: Expect status to be 200
        $response->assertStatus(200);
    }

    public function test_assert_status_throws_when_status_mismatches()
    {
        $this->expectException(\PHPUnit\Framework\ExpectationFailedException::class);
        $this->expectExceptionMessage('Expected response status 404 but got 500.');

        // Arrange: Create a response with status 500
        $response = new TestResponse('Error content', 500);

        // Act: This should fail
        $response->assertStatus(404);
    }
}
