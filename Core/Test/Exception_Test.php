<?php
/**
 * 
 */
namespace X\Core\Test;

/**
 * 
 */
use X\Core\Exception;

/**
 * Exception test case.
 */
class Exception_Test extends \PHPUnit_Framework_TestCase {
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }
    
    /**
     * @expectedException X\Core\Exception
     */
    public function test_throw() {
        throw new Exception('phpunit test');
    }
}

