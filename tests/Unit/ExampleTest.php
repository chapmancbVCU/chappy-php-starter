<?php
namespace Tests\Unit;
use Core\DB;
use App\Models\Users;
use Tests\ApplicationTestCase;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class ExampleTest extends ApplicationTestCase {
    public function test_unit_example_1() {
        $string1 = 'testing';
        $string2 = 'testing';

        $this->assertSame($string1, $string2);
        $user = Users::findById(1);
        dd($user);
    }
}
