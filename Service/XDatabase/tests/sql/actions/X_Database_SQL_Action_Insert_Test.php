<?php
/**
 * SQLBuilderActionInsertTest.php
 * 
 * @author      Michael Luthor <michael.the.ranidae@gmail.com>
 * @version     $Id$
 */

/**
 * SQLBuilderActionInsert test case.
 */
class X_Database_SQL_Action_Insert_Test extends PHPUnit_Framework_TestCase {
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
     * Tests SQLBuilderActionInsert->into()
     */
    public function testInto() {
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        try {
            $insert->toString();
            $this->assertTrue(false);
        } catch ( X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
        
        $insert = X\Database\SQL\SQLBuilder::build()->insert()->into('table');
        try {
            $insert->toString();
            $this->assertTrue(false);
        } catch ( X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
        
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        $expected = "INSERT INTO `table` (`col1`,`col2`) VALUES ('val1','val2')";
        $actual = $insert->into('table')->values(array('col1'=>'val1', 'col2'=>'val2'))->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionInsert->columns()
     */
    public function testColumns() {
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        $expected = "INSERT INTO `table` VALUES ('val1','val2')";
        $actual = $insert->into('table')->values(array('val1', 'val2'))->toString();
        $this->assertEquals($expected, $actual);
        
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        $expected = "INSERT INTO `table` (`col1`,`col2`) VALUES ('val1','val2')";
        $actual = $insert->into('table')->values(array('val1', 'val2'))->columns('col1', 'col2')->toString();
        $this->assertEquals($expected, $actual);
        
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        try {
            $insert->into('table')->values(array('val1', 'val2'))->columns('col1', 'col2', 'col3')->toString();
            $this->assertTrue(false);
        } catch ( X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
    }
    
    /**
     * Tests SQLBuilderActionInsert->values()
     */
    public function testValues() {
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        $expected = "INSERT INTO `table` (`col1`,`col2`) VALUES ('val1','val2')";
        $actual = $insert->into('table')->values(array('col1'=>'val1', 'col2'=>'val2'))->toString();
        $this->assertEquals($expected, $actual);
        
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        $expected = "INSERT INTO `table` VALUES ('val1','val2')";
        $actual = $insert->into('table')->values(array('val1', 'val2'))->toString();
        $this->assertEquals($expected, $actual);
        
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        $expected = "INSERT INTO `table` VALUES ('val1')";
        $actual = $insert->into('table')->values('val1')->toString();
        $this->assertEquals($expected, $actual);
        
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        $expected = "INSERT INTO `table` VALUES ('val1','val2')";
        $actual = $insert->into('table')->values('val1', 'val2')->toString();
        $this->assertEquals($expected, $actual);
        
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        $expected = "INSERT INTO `table` VALUES ('val1','val2'),('val3','val4')";
        $actual = $insert->into('table')->values('val1', 'val2')->values('val3', 'val4')->toString();
        $this->assertEquals($expected, $actual);
        
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        $expected = "INSERT INTO `table` (`col1`,`col2`) VALUES (DEFAULT,'val2')";
        $actual = $insert->into('table')->values(array('col1'=>new X\Database\SQL\Other\DefaultValue(), 'col2'=>'val2'))->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionInsert->select()
     */
    public function testSelect() {
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        $select = X\Database\SQL\SQLBuilder::build()->select()->from('other_table')->limit(5);
        $expected = "INSERT INTO `table` SELECT * FROM `other_table` LIMIT 5";
        $actual = $insert->into('table')->select($select)->toString();
        $this->assertEquals($expected, $actual);
        
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        $expected = "INSERT INTO `table` SELECT * FROM `other_table`";
        $actual = $insert->into('table')->select('SELECT * FROM `other_table`')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionInsert->onDuplicateKeyUpdate()
     */
    public function testOnDuplicateKeyUpdate() {
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        $expected = "INSERT INTO `table` VALUES ('val') ON DUPLICATE KEY UPDATE col1 = col1+1,col2 = col2+1";
        $actual = $insert->into('table')->values('val')->onDuplicateKeyUpdate('col1 = col1+1', 'col2 = col2+1')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionInsert->ignoreOnDuplicateKey()
     */
    public function testIgnoreOnDuplicateKey() {
        $value1WithKey = array('col1'=>'val1', 'col2'=>'val2');
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        $expected = "INSERT IGNORE INTO `table` (`col1`,`col2`) VALUES ('val1','val2')";
        $actual = $insert->into('table')->values($value1WithKey)->ignoreOnDuplicateKey()->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionInsert->priority()
     */
    public function testPriority() {
        $value1WithKey = array('col1'=>'val1', 'col2'=>'val2');
        $insert = X\Database\SQL\SQLBuilder::build()->insert();
        $expected = "INSERT LOW_PRIORITY INTO `table` (`col1`,`col2`) VALUES ('val1','val2')";
        $actual = $insert->into('table')->values($value1WithKey)->priority(X\Database\SQL\Action\Insert::PRIORITY_LOW)->toString();
        $this->assertEquals($expected, $actual);
    }
}

