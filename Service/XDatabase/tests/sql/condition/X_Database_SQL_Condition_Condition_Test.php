<?php
/**
 * SQLConditionTest.php
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */
/**
 * SQLCondition test case.
 */
class X_Database_SQL_Condition_Condition_Test extends PHPUnit_Framework_TestCase {
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
     * Tests SQLCondition->__construct()
     */
    public function test__construct() {
        $condition = new X\Database\SQL\Condition\Condition('col', X\Database\SQL\Condition\Condition::OPERATOR_EQUAL, 'val');
        $this->assertInstanceOf('X\Database\SQL\Condition\Condition', $condition);
    }
    
    /**
     * Tests SQLCondition->toString()
     */
    public function testToString() {
        $condition = new X\Database\SQL\Condition\Condition('c', X\Database\SQL\Condition\Condition::OPERATOR_EQUAL, 'v');
        $this->assertEquals("`c` = 'v'", $condition->toString());
        
        $condition = new X\Database\SQL\Condition\Condition('c', X\Database\SQL\Condition\Condition::OPERATOR_NOT_EQUAL, 'v');
        $this->assertEquals("`c` <> 'v'", $condition->toString());
        
        $condition = new X\Database\SQL\Condition\Condition('c', X\Database\SQL\Condition\Condition::OPERATOR_GREATER_THAN, 'v');
        $this->assertEquals("`c` > 'v'", $condition->toString());
        
        $condition = new X\Database\SQL\Condition\Condition('c', X\Database\SQL\Condition\Condition::OPERATOR_GREATER_OR_EQUAL, 'v');
        $this->assertEquals("`c` >= 'v'", $condition->toString());
        
        $condition = new X\Database\SQL\Condition\Condition('c', X\Database\SQL\Condition\Condition::OPERATOR_LESS_THAN, 'v');
        $this->assertEquals("`c` < 'v'", $condition->toString());
        
        $condition = new X\Database\SQL\Condition\Condition('c', X\Database\SQL\Condition\Condition::OPERATOR_LIKE, 'v');
        $this->assertEquals("`c` LIKE 'v'", $condition->toString());
        
        $condition = new X\Database\SQL\Condition\Condition('c', X\Database\SQL\Condition\Condition::OPERATOR_START_WITH, 'v');
        $this->assertEquals("`c` LIKE 'v%%'", $condition->toString());
        
        $condition = new X\Database\SQL\Condition\Condition('c', X\Database\SQL\Condition\Condition::OPERATOR_END_WITH, 'v');
        $this->assertEquals("`c` LIKE '%%v'", $condition->toString());
        
        $condition = new X\Database\SQL\Condition\Condition('c', X\Database\SQL\Condition\Condition::OPERATOR_BETWEEN, array(1,2));
        $this->assertEquals("`c` BETWEEN ('1', '2')", $condition->toString());
        
        $condition = new X\Database\SQL\Condition\Condition('c', X\Database\SQL\Condition\Condition::OPERATOR_IN, array(1,2));
        $this->assertEquals("`c` IN ('1','2')", $condition->toString());
        
        $condition = new X\Database\SQL\Condition\Condition('c', X\Database\SQL\Condition\Condition::OPERATOR_NOT_IN, array(1,2));
        $this->assertEquals("`c` NOT IN ('1','2')", $condition->toString());
    }
}

