<?php
namespace Tests\Feature;
use App\Models\Users;
use Core\Lib\Testing\ApplicationTestCase;

/**
 * Undocumented class
 */
class FeatureTest extends ApplicationTestCase {
    public function test_feature_example_1() {
        $user = Users::findById(1);
        // dd($user);
        $name = $user->username;
        $output = $this->controllerOutput('admindashboard', 'details', ['1']);

        $this->assertStringContainsString(
            'Details for '.$name,
            $output
        );
    }
}
