<?php
/**
 * X_Database_SQL_Action_AlterTable.php
 *
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version 0.0.0
 */

/**
 * AlterTable test case.
 */
class X_Database_SQL_Action_AlterTable extends PHPUnit_Framework_TestCase {
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
     * Tests AlterTable->name()
     */
    public function testName() {
        $alterTable = new \X\Database\SQL\Action\AlterTable();
        $actual = $alterTable->name('table')->dropColumn('column')->toString();
        $expected = 'ALTER TABLE `table` DROP COLUMN `column`';
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests AlterTable->AddColumn()
     */
    public function testAddColumn() {
        $alterTable = new \X\Database\SQL\Action\AlterTable();
        $actual = $alterTable->name('table')->addColumn('column', 'VARCHAR(256)')->toString();
        $expected = 'ALTER TABLE `table` ADD `column` VARCHAR(256)';
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests AlterTable->addIndex()
     */
    public function testAddIndex() {
        $alterTable = new \X\Database\SQL\Action\AlterTable();
        $actual = $alterTable->name('table')->addIndex('new_index', array('col1', 'col2'))->toString();
        $expected = 'ALTER TABLE `table` ADD INDEX `new_index` (`col1`,`col2`)';
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests AlterTable->addPrimaryKey()
     */
    public function testAddPrimaryKey() {
        $alterTable = new \X\Database\SQL\Action\AlterTable();
        $actual = $alterTable->name('table')->addPrimaryKey(array('col'))->toString();
        $expected = 'ALTER TABLE `table` ADD PRIMARY KEY (`col`)';
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests AlterTable->addUnique()
     */
    public function testAddUnique() {
        $alterTable = new \X\Database\SQL\Action\AlterTable();
        $actual = $alterTable->name('table')->addUnique(array('col1'))->toString();
        $expected = 'ALTER TABLE `table` ADD UNIQUE ( `col1` )';
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests AlterTable->changeColumn()
     */
    public function testChangeColumn() {
        $alterTable = new \X\Database\SQL\Action\AlterTable();
        $actual = $alterTable->name('table')->changeColumn('col', 'new-col', 'VARCHAR(255)')->toString();
        $expected = 'ALTER TABLE `table` CHANGE COLUMN `col` `new-col` VARCHAR(255)';
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests AlterTable->dropColumn()
     */
    public function testDropColumn() {
        $alterTable = new \X\Database\SQL\Action\AlterTable();
        $actual = $alterTable->name('table')->dropColumn('col')->toString();
        $expected = 'ALTER TABLE `table` DROP COLUMN `col`';
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests AlterTable->dropPrimaryKey()
     */
    public function testDropPrimaryKey() {
        $alterTable = new \X\Database\SQL\Action\AlterTable();
        $actual = $alterTable->name('table')->dropPrimaryKey()->toString();
        $expected = 'ALTER TABLE `table` DROP PRIMARY KEY';
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests AlterTable->dropIndex()
     */
    public function testDropIndex() {
        $alterTable = new \X\Database\SQL\Action\AlterTable();
        $actual = $alterTable->name('table')->dropIndex('index_x')->toString();
        $expected = 'ALTER TABLE `table` DROP INDEX index_x';
        $this->assertEquals($expected, $actual);
    }
}