<?php
/**
 *
 */
namespace X\Core\Test\Module;

/**
 * 
 */
use X\Core\X;

/**
 * ModuleManagement test case.
 */
class ModuleManagement_Test extends \PHPUnit_Framework_TestCase {
    /**
     * @var \X\Core\Module\ModuleManagement
     */
    private $ModuleManagement;
    
    /**
     * @var array
     */
    private $oldConfig = array();
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->ModuleManagement = X::system()->getModuleManager();
        $this->oldConfig = $this->ModuleManagement->getConfiguration()->getAll();
        $this->ModuleManagement->create('PHPUnitTestModule');
        $this->ModuleManagement->enable('PHPUnitTestModule');
        $this->ModuleManagement->start();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        $this->ModuleManagement->stop();
        $this->ModuleManagement->delete('PHPUnitTestModule');
        $this->ModuleManagement->getConfiguration()->setAll($this->oldConfig);
        $this->ModuleManagement->getConfiguration()->save();
        parent::tearDown ();
    }
    
    /**
     * Tests ModuleManagement->start()
     */
    public function testStart() {
        $modules = $this->ModuleManagement->getList();
        $this->assertContains('PHPUnitTestModule', $modules);
    }
    
    /**
     * Tests ModuleManagement->getConfiguration()
     */
    public function testGetConfiguration() {
        // TODO Auto-generated ModuleManagement_Test->testGetConfiguration()
        $this->markTestIncomplete ( "getConfiguration test not implemented" );
        
        $this->ModuleManagement->getConfiguration(/* parameters */);
    }
    
    /**
     * Tests ModuleManagement->run()
     */
    public function testRun() {
        // TODO Auto-generated ModuleManagement_Test->testRun()
        $this->markTestIncomplete ( "run test not implemented" );
        
        $this->ModuleManagement->run(/* parameters */);
    }
    
    /**
     * Tests ModuleManagement->getList()
     */
    public function testGetList() {
        // TODO Auto-generated ModuleManagement_Test->testGetList()
        $this->markTestIncomplete ( "getList test not implemented" );
        
        $this->ModuleManagement->getList(/* parameters */);
    }
    
    /**
     * Tests ModuleManagement->has()
     */
    public function testHas() {
        // TODO Auto-generated ModuleManagement_Test->testHas()
        $this->markTestIncomplete ( "has test not implemented" );
        
        $this->ModuleManagement->has(/* parameters */);
    }
    
    /**
     * Tests ModuleManagement->get()
     */
    public function testGet() {
        // TODO Auto-generated ModuleManagement_Test->testGet()
        $this->markTestIncomplete ( "get test not implemented" );
        
        $this->ModuleManagement->get(/* parameters */);
    }
    
    /**
     * Tests ModuleManagement->isEnable()
     */
    public function testIsEnable() {
        // TODO Auto-generated ModuleManagement_Test->testIsEnable()
        $this->markTestIncomplete ( "isEnable test not implemented" );
        
        $this->ModuleManagement->isEnable(/* parameters */);
    }
    
    /**
     * Tests ModuleManagement->enable()
     */
    public function testEnable() {
        // TODO Auto-generated ModuleManagement_Test->testEnable()
        $this->markTestIncomplete ( "enable test not implemented" );
        
        $this->ModuleManagement->enable(/* parameters */);
    }
    
    /**
     * Tests ModuleManagement->disable()
     */
    public function testDisable() {
        // TODO Auto-generated ModuleManagement_Test->testDisable()
        $this->markTestIncomplete ( "disable test not implemented" );
        
        $this->ModuleManagement->disable(/* parameters */);
    }
    
    /**
     * Tests ModuleManagement->create()
     */
    public function testCreate() {
        // TODO Auto-generated ModuleManagement_Test->testCreate()
        $this->markTestIncomplete ( "create test not implemented" );
        
        $this->ModuleManagement->create(/* parameters */);
    }
    
    /**
     * Tests ModuleManagement->setDefault()
     */
    public function testSetDefault() {
        // TODO Auto-generated ModuleManagement_Test->testSetDefault()
        $this->markTestIncomplete ( "setDefault test not implemented" );
        
        $this->ModuleManagement->setDefault(/* parameters */);
    }
    
    /**
     * Tests ModuleManagement->isDefault()
     */
    public function testIsDefault() {
        // TODO Auto-generated ModuleManagement_Test->testIsDefault()
        $this->markTestIncomplete ( "isDefault test not implemented" );
        
        $this->ModuleManagement->isDefault(/* parameters */);
    }
    
    /**
     * Tests ModuleManagement->delete()
     */
    public function testDelete() {
        // TODO Auto-generated ModuleManagement_Test->testDelete()
        $this->markTestIncomplete ( "delete test not implemented" );
        
        $this->ModuleManagement->delete(/* parameters */);
    }
    
    /**
     * Tests ModuleManagement->migrateCreate()
     */
    public function testMigrateCreate() {
        // TODO Auto-generated ModuleManagement_Test->testMigrateCreate()
        $this->markTestIncomplete ( "migrateCreate test not implemented" );
        
        $this->ModuleManagement->migrateCreate(/* parameters */);
    }
    
    /**
     * Tests ModuleManagement->migrateUp()
     */
    public function testMigrateUp() {
        // TODO Auto-generated ModuleManagement_Test->testMigrateUp()
        $this->markTestIncomplete ( "migrateUp test not implemented" );
        
        $this->ModuleManagement->migrateUp(/* parameters */);
    }
    
    /**
     * Tests ModuleManagement->migrateDown()
     */
    public function testMigrateDown() {
        // TODO Auto-generated ModuleManagement_Test->testMigrateDown()
        $this->markTestIncomplete ( "migrateDown test not implemented" );
        
        $this->ModuleManagement->migrateDown(/* parameters */);
    }
}

