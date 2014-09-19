<?php
/**
 * SQLConditionConnectorTest.php
 *
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */
/**
 * SQLConditionConnector test case.
 */
class X_Database_SQL_Condition_Connector_Test extends PHPUnit_Framework_TestCase {
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
     * Tests SQLConditionConnector->__construct()
     */
    public function test__construct() {
        $connector = new X\Database\SQL\Condition\Connector(X\Database\SQL\Condition\Connector::CONNECTOR_AND);
        $this->assertInstanceOf('X\Database\SQL\Condition\Connector', $connector);
    }
    
    /**
     * Tests SQLConditionConnector->toString()
     */
    public function testToString() {
        $connector = new X\Database\SQL\Condition\Connector(X\Database\SQL\Condition\Connector::CONNECTOR_AND);
        $this->assertEquals('AND', $connector->toString());
        
        $connector = new X\Database\SQL\Condition\Connector(X\Database\SQL\Condition\Connector::CONNECTOR_OR);
        $this->assertEquals('OR', $connector->toString());
    }
}