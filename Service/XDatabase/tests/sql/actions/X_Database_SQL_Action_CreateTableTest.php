<?php
/**
 * X_Database_SQL_Action_AlterTable.php
 *
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version 0.0.0
 */

/**
 * CreateTable test case.
 */
class X_Database_SQL_Action_CreateTableTest extends PHPUnit_Framework_TestCase {
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
     * Tests CreateTable->columns()
     */
    public function testColumns() {
        $createTable = new \X\Database\SQL\Action\CreateTable();
        $actual = $createTable->name('table')->columns(array(
            'col1'  => 'VARCHAR(256)',
            'col2'  => 'INT'
        ))->toString();
        $expected = 'CREATE TABLE `table` (`col1` VARCHAR(256),`col2` INT)';
        $this->assertEquals($expected, $actual);
    }
}

