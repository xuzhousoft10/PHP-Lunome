<?php
/**
 * SQLFuncCountTest.php
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */

/**
 * SQLFuncCount test case.
 */
class X_Database_SQL_Func_Count_Test extends PHPUnit_Framework_TestCase {
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
     * Tests SQLFuncCount->toString()
     */
    public function testToString() {
        $count = new X\Database\SQL\Func\Count();
        $this->assertEquals('COUNT(*)', $count->toString());
    }
}

