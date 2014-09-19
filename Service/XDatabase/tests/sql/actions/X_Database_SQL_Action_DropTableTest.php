<?php
/**
 * X_Database_SQL_Action_AlterTable.php
 *
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version 0.0.0
 */

/**
 * DropTable test case.
 */
class X_Database_SQL_Action_DropTableTest extends PHPUnit_Framework_TestCase {
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
        $dropTable = new X\Database\SQL\Action\DropTable();
        $actual = $dropTable->name('table')->toString();
        $expected = 'DROP TABLE `table`';
        $this->assertEquals($expected, $actual);
    }
}