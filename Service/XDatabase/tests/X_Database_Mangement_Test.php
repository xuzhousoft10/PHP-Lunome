<?php
/**
 * X_Database_Mangement_Test.php
 *
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
/**
 * Management test case.
 */
class X_Database_Mangement_Test extends PHPUnit_Framework_TestCase {
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
     * Tests X\Database\Management::getManager()->addDb()
     */
    public function testAddDb() {
        X\Database\Management::getManager()->addDb('test1', array('dsn'=>'mysql:host=127.0.0.1;dbname=x_test_db', 'username'=>'root', 'password'=>''));
        $this->assertTrue(X\Database\Management::getManager()->hasDb('test1'));
        $this->assertFalse(X\Database\Management::getManager()->hasDb('test-non-exists'));
    
        try {
            X\Database\Management::getManager()->addDb('test1', array());
            $this->assertTrue(false);
        } catch ( X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
    }
    
    /**
     * Tests X\Database\Management::getManager()->getDb()
     */
    public function testGetDb() {
       X\Database\Management::getManager()->addDb('test2', array('dsn'=>'mysql:host=127.0.0.1;dbname=x_test_db', 'username'=>'root', 'password'=>''));
       $this->assertInstanceOf('X\Database\Database', X\Database\Management::getManager()->getDb('test2'));
    }
    
    /**
     * Tests X\Database\Management::getManager()->hasDb()
     */
    public function testHasDb() {
        X\Database\Management::getManager()->addDb('test3', array('dsn'=>'mysql:host=127.0.0.1;dbname=x_test_db', 'username'=>'root', 'password'=>''));
        $this->assertTrue(X\Database\Management::getManager()->hasDb('test1'));
        $this->assertFalse(X\Database\Management::getManager()->hasDb('test-non-exists'));
    }
    
    /**
     * Tests X\Database\Management::getManager()->switchTo()
     */
    public function testSwitchTo() {
        try {
            X\Database\Management::getManager()->switchTo('non-exists-db');
            $this->assertTrue(false);
        } catch ( X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
        
        X\Database\Management::getManager()->addDb('test4', array('dsn'=>'mysql:host=127.0.0.1;dbname=x_test_db', 'username'=>'root', 'password'=>''));
        X\Database\Management::getManager()->switchTo('test4');
        
        $this->assertEquals('test4', X\Database\Management::getManager()->getCurrentDbName());
    }
    
    /**
     * X\Database\Management::getManager()->getCurrentDbName()
     */
    public function testGetCurrentDbName() {
        X\Database\Management::getManager()->addDb('test5', array('dsn'=>'mysql:host=127.0.0.1;dbname=x_test_db', 'username'=>'root', 'password'=>''));
        X\Database\Management::getManager()->switchTo('test5');
        
        $this->assertEquals('test5', X\Database\Management::getManager()->getCurrentDbName());
    }
    
    /**
     * X\Database\Management::getManager()->removeDb()
     */
    public function testRemoveDb() {
        try {
            X\Database\Management::getManager()->removeDb('non-exists-db');
            $this->assertTrue(false);
        } catch ( X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
        
        X\Database\Management::getManager()->addDb('test6', array('dsn'=>'mysql:host=127.0.0.1;dbname=x_test_db', 'username'=>'root', 'password'=>''));
        $this->assertTrue(X\Database\Management::getManager()->hasDb('test6'));
        X\Database\Management::getManager()->removeDb('test6');
        $this->assertFalse(X\Database\Management::getManager()->hasDb('test6'));
        
        X\Database\Management::getManager()->addDb('test7', array('dsn'=>'mysql:host=127.0.0.1;dbname=x_test_db', 'username'=>'root', 'password'=>''));
        X\Database\Management::getManager()->switchTo('test7');
        X\Database\Management::getManager()->removeDb('test7');
        $this->assertEquals(X\Database\Management::DEFAULT_DB_NAME, X\Database\Management::getManager()->getCurrentDbName());
    }
}