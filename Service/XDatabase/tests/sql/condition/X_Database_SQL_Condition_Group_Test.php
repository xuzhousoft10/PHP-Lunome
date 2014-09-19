<?php
/**
 * SQLConditionGroupTest.php
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */
/**
 * SQLConditionGroup test case.
 */
class X_Database_SQL_Condition_Group_Test extends PHPUnit_Framework_TestCase {
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
     * Tests SQLConditionGroup->__construct()
     */
    public function test__construct() {
        $group = new X\Database\SQL\Condition\Group(X\Database\SQL\Condition\Group::POSITION_START);
        $this->assertInstanceOf('X\Database\SQL\Condition\Group', $group);
    }
    
    /**
     * Tests SQLConditionGroup->toString()
     */
    public function testToString() {
        $group = new X\Database\SQL\Condition\Group(X\Database\SQL\Condition\Group::POSITION_START);
        $this->assertEquals('(', $group->toString());
        
        $group = new X\Database\SQL\Condition\Group(X\Database\SQL\Condition\Group::POSITION_END);
        $this->assertEquals(')', $group->toString());
    }
}