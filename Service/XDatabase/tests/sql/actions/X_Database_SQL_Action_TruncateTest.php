<?php
/**
 * X_Database_SQL_Action_AlterTable.php
 *
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version 0.0.0
 */
/**
 * Truncate test case.
 */
class X_Database_SQL_Action_TruncateTest extends PHPUnit_Framework_TestCase {
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
        $truncate = new \X\Database\SQL\Action\Truncate();
        $actual = $truncate->name('table')->toString();
        $expected = 'TRUNCATE TABLE `table`';
        $this->assertEquals($expected, $actual);
    }
}