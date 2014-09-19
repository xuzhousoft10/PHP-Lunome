<?php
/**
 * X_Database_SQL_Action_AlterTable.php
 *
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version 0.0.0
 */

/**
 * Rename test case.
 */
class X_Database_SQL_Action_RenameTest extends PHPUnit_Framework_TestCase {
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
     * testToString
     */
    public function testToString() {
        $rename = new X\Database\SQL\Action\Rename();
        $actual = $rename->name('table_old')->newName('table_new')->toString();
        $expected = 'RENAME TABLE `table_old` TO `table_new`';
        $this->assertEquals($expected, $actual);
    }
}

