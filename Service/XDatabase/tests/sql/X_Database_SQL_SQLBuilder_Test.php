<?php
/**
 * SQLBuilderTest.php
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */
/**
 * SQLBuilder test case.
 */
class X_Database_SQL_SQLBuilder_Test extends PHPUnit_Framework_TestCase {
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
     * Tests SQLBuilder::build()
     */
    public function testBuild() {
        $builder = \X\Database\SQL\SQLBuilder::build();
        $this->assertInstanceOf('\X\Database\SQL\SQLBuilder', $builder);
    }
    
    /**
     * Tests SQLBuilder->select()
     */
    public function testSelect() {
        $builder = \X\Database\SQL\SQLBuilder::build()->select();
        $this->assertInstanceOf('\X\Database\SQL\Action\Select', $builder);
    }
    
    /**
     * Tests SQLBuilder->insert()
     */
    public function testInsert() {
        $builder = \X\Database\SQL\SQLBuilder::build()->insert();
        $this->assertInstanceOf('\X\Database\SQL\Action\Insert', $builder);
    }
    
    /**
     * Tests SQLBuilder->update()
     */
    public function testUpdate() {
        $builder = \X\Database\SQL\SQLBuilder::build()->update();
        $this->assertInstanceOf('\X\Database\SQL\Action\Update', $builder);
    }
    
    /**
     * Tests SQLBuilder->delete()
     */
    public function testDelete() {
        $builder = \X\Database\SQL\SQLBuilder::build()->delete();
        $this->assertInstanceOf('\X\Database\SQL\Action\Delete', $builder);
    }
}