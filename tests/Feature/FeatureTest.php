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
    public function test_feature_example_1() {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Welcome');
    }

    
}
