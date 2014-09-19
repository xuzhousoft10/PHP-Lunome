<?php
/**
 * SQLFuncRandTest.php
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */

/**
 * SQLFuncRand test case.
 */
class X_Database_SQL_Func_Rand_Test extends PHPUnit_Framework_TestCase {
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
     * Tests SQLFuncRand->toString()
     */
    public function testToString() {
        $rand = new X\Database\SQL\Func\Rand();
        $this->assertEquals('RAND()', $rand->toString());
    }
}