<?php
/**
 * SQLActionWithConditionTest.php
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */

/**
 * SQLActionWithCondition test case.
 */
class X_Database_SQL_Action_Action_With_Condition_Test extends PHPUnit_Framework_TestCase {
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
     * Tests SQLActionWithCondition->where()
     */
    public function testWhere() {
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table` WHERE `id` = '1'";
        $actual = $select->from('table')->where(X\Database\SQL\Condition\Builder::build(array('id'=>1)))->toString();
        $this->assertEquals($expected, $actual);
        
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table` WHERE `col1` = '1' AND `col2` = '2'";
        $actual = $select->from('table')->where( array('col1'=>1, 'col2'=>2) )->toString();
        $this->assertEquals($expected, $actual);
        
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table` WHERE col1=>1 && col2=>2";
        $actual = $select->from('table')->where( "col1=>1 && col2=>2" )->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLActionWithCondition->orderBy()
     */
    public function testOrderBy() {
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1` ORDER BY `id` ,`age` ASC";
        $actual = $select->from('table1')->orderBy('id')->orderBy('age', 'ASC')->toString();
        $this->assertEquals($expected, $actual);
        
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1` ORDER BY RAND() ";
        $actual = $select->from('table1')->orderBy( new X\Database\SQL\Func\Rand() )->toString();
        $this->assertEquals($expected, $actual);
        
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1` ORDER BY `id` ,`age` ACS,RAND() ";
        $actual = $select->from('table1')->orderby(array('id'),array('age', 'ACS'),array(new X\Database\SQL\Func\Rand()))->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLActionWithCondition->limit()
     */
    public function testLimit() {
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1` LIMIT 1";
        $actual = $select->from('table1')->limit(1)->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLActionWithCondition->offset()
     */
    public function testOffset() {
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1` OFFSET 1";
        $actual = $select->from('table1')->offset(1)->toString();
        $this->assertEquals($expected, $actual);
    }
}