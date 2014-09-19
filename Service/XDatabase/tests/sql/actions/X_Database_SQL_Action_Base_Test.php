<?php
/**
 * SQLActionBaseTest.php
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */

/**
 * SQLActionBase test case.
 */
class X_Database_SQL_Action_Base_Test extends PHPUnit_Framework_TestCase {
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
     * Tests SQLActionBase->toString()
     */
    public function testToString() {
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table` WHERE `id` = '1'";
        $actual = $select->from('table')->where(X\Database\SQL\Condition\Builder::build(array('id'=>1)))->toString();
        $this->assertEquals($expected, $actual);
    }
}