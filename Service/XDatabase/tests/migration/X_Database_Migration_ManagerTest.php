<?php
/**
 * SQLBuilderTest.php
 *
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */

/**
 * Manager test case.
 */
class X_Database_Migration_ManagerTest extends PHPUnit_Framework_TestCase {
    /**
     * 
     */
    public function __construct() {
        \X\Database\Migration\Management::manager()->setMigrationPath(dirname(__FILE__).'/migrations');
    }
    
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
     * Tests Manager->up()
     */
    public function testUp() {
        \X\Database\Migration\Management::manager()->up();
    }
    
    /**
     * Tests Manager->down()
     */
    public function testDown() {
        \X\Database\Migration\Management::manager()->down();
    }
}

