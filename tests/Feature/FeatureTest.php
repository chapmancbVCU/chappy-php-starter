<?php
namespace Tests\Feature;
use App\Models\Users;
use App\Controllers\HomeController;
use Core\Lib\Testing\ApplicationTestCase;

/**
 * Undocumented class
 */
class FeatureTest extends ApplicationTestCase {
    public function test_feature_example_1() {
        // $user = Users::findById(1);
        // dd($user);
        $output = $this->controllerOutput('admindashboard', 'details', ['2']);

        dd($output);
        $this->assertStringContainsString(
            'A lightweight and modern PHP framework built for simplicity, speed, and developer happiness.',
            $output
        );
    }
}
