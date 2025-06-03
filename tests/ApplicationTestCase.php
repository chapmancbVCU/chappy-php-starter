<?php
namespace Tests;
use PHPUnit\Framework\TestCase;

/**
 * Abstract class for test cases.
 */
abstract class ApplicationTestCase extends TestCase {
    /**
     * Implements setUp function from TestCase class.
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();
    }
}