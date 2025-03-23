<?php
namespace Tests;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class Test extends TestCase {
    public function testTest() {
        $string1 = 'testing';
        $string2 = 'testing';

        $this->assertSame($string1, $string2);
    }
}
