<?php
/**
 * SQLBuilderActionDeleteTest.php
 * 
 * @author      Michael Luthor <michael.the.ranidae@gmail.com>
 * @version     $Id$
 */
/**
 * SQLBuilderActionDelete test case.
 */
class X_Database_SQL_Action_Delete_Test extends PHPUnit_Framework_TestCase {
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
     * Tests SQLBuilderActionDelete->lowPriority()
     */
    public function testLowPriority() {
        $delete = X\Database\SQL\SQLBuilder::build()->delete();
        $expected = 'DELETE LOW_PRIORITY FROM `table`';
        $actual = $delete->from('table')->lowPriority()->toString();
        $this->assertEquals($expected, $actual);
        
        $delete = X\Database\SQL\SQLBuilder::build()->delete();
        $expected = 'DELETE FROM `table`';
        $actual = $delete->from('table')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionDelete->quick()
     */
    public function testQuick() {
        $delete = X\Database\SQL\SQLBuilder::build()->delete();
        $expected = 'DELETE QUICK FROM `table`';
        $actual = $delete->from('table')->quick()->toString();
        $this->assertEquals($expected, $actual);
        
        $delete = X\Database\SQL\SQLBuilder::build()->delete();
        $expected = 'DELETE FROM `table`';
        $actual = $delete->from('table')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionDelete->ignore()
     */
    public function testIgnore() {
        $delete = X\Database\SQL\SQLBuilder::build()->delete();
        $expected = 'DELETE IGNORE FROM `table`';
        $actual = $delete->from('table')->ignore()->toString();
        $this->assertEquals($expected, $actual);
        
        $delete = X\Database\SQL\SQLBuilder::build()->delete();
        $expected = 'DELETE FROM `table`';
        $actual = $delete->from('table')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionDelete->from()
     */
    public function testFrom() {
        $delete = X\Database\SQL\SQLBuilder::build()->delete();
        try {
            $delete->toString();
            $this->assertTrue(false);
        } catch ( X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
        
        $delete = X\Database\SQL\SQLBuilder::build()->delete();
        $expected = 'DELETE FROM `table`';
        $actual = $delete->from('table')->toString();
        $this->assertEquals($expected, $actual);
        
        $delete = X\Database\SQL\SQLBuilder::build()->delete();
        $expected = 'DELETE FROM `table1`,`table2`';
        $actual = $delete->from('table1', 'table2')->toString();
        $this->assertEquals($expected, $actual);
        
        $delete = X\Database\SQL\SQLBuilder::build()->delete();
        $expected = 'DELETE FROM `table1`,`table2`';
        $actual = $delete->from(array('table1', 'table2'))->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionDelete->orderBy()
     */
    public function testOrderBy() {
        $delete = X\Database\SQL\SQLBuilder::build()->delete();
        try { 
            $delete->from('table1','table2')->orderBy('id', 'ASC')->toString();
            $this->assertTrue(false);
        }catch ( X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
    }
}