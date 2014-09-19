<?php
/**
 * SQLBuilderActionUpdateTest.php
 * 
 * @author Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */

/**
 * SQLBuilderActionUpdate test case.
 */
class X_Database_SQL_Action_Update_Test extends PHPUnit_Framework_TestCase {
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
     * Tests SQLBuilderActionUpdate->lowPriority()
     */
    public function testLowPriority() {
        $update = X\Database\SQL\SQLBuilder::build()->update();
        $expected = "UPDATE LOW_PRIORITY `table` SET `name`='new-name'";
        $actual = $update->table('table')->set('name', 'new-name')->lowPriority()->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionUpdate->ignore()
     */
    public function testIgnore() {
        $update = X\Database\SQL\SQLBuilder::build()->update();
        $expected = "UPDATE IGNORE `table` SET `name`='new-name'";
        $actual = $update->table('table')->set('name', 'new-name')->ignore()->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionUpdate->table()
     */
    public function testTable() {
        $update = X\Database\SQL\SQLBuilder::build()->update();
        $expected = "UPDATE `table` SET `name`='new-name'";
        $actual = $update->table('table')->set('name', 'new-name')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionUpdate->set()
     */
    public function testSet() {
        $update = X\Database\SQL\SQLBuilder::build()->update();
        $expected = "UPDATE `table` SET `name`='new-name'";
        $actual = $update->table('table')->set('name', 'new-name')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionUpdate->setValues()
     */
    public function testSetValues() {
        $update = X\Database\SQL\SQLBuilder::build()->update();
        $expected = "UPDATE `table` SET `name`='new-name'";
        $actual = $update->table('table')->setValues(array('name'=>'new-name'))->toString();
        $this->assertEquals($expected, $actual);
    }
}