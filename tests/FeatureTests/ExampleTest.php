<?php
namespace Tests\FeatureTests;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class ExampleTest extends TestCase {
    public function testTest() {
        $string1 = 'testing';
        $string2 = 'testing';

        $this->assertSame($string1, $string2);
    }
}
