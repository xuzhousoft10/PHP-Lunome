<?php
/**
 * DatabaseTest.php
 *
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */
/**
 * Database test case.
 */
class X_Database_Database_Test extends PHPUnit_Framework_TestCase {
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
     * Tests Database->__construct()
     */
    public function test__construct() {
        $config = array(
            'dsn'=>'mysql:host=127.0.0.1;dbname=x_test_db', 
            'username'=>'root',
            'password'=>''
        );
        
        $db = new X\Database\Database($config);
        $this->assertInstanceOf('X\Database\Database', $db);
    }
}

