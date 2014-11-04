<?php
/**
 * 
 */
namespace X\Core\Test\Service;

/**
 * 
 */
use X\Core\X;
use X\Core\Service\XService;

/**
 * ServiceManagement test case.
 */
class ServiceManagement_Test extends \PHPUnit_Framework_TestCase {
    /**
     * @var \X\Core\Service\ServiceManagement
     */
    private $ServiceManagement;
    
    /**
     * @param string $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->ServiceManagement = X::system()->getServiceManager();
    }
    
    /**
     * @var array
     */
    private $oldConfig = array(); 
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->oldConfig = $this->ServiceManagement->getConfiguration()->getAll();
        $this->ServiceManagement->getConfiguration()->setAll(array());
        $testService = array ('enable'=>true,'class' =>'\\X\\Core\\Test\\Service\\PHPUnitTestService');
        $this->ServiceManagement->getConfiguration()->set('PHPUnitTestService', $testService);
        $testService = array ('enable'=>true,'class' =>'\\X\\Core\\Test\\Service\\PHPUnitTestServiceNotDelay', 'delay'=>false);
        $this->ServiceManagement->getConfiguration()->set('PHPUnitTestServiceNotDelay', $testService);
        $testService = array ('enable'=>false,'class' =>'\\X\\Core\\Test\\Service\\PHPUnitTestServiceNotEnabled');
        $this->ServiceManagement->getConfiguration()->set('PHPUnitTestServiceNotEnabled', $testService);
        $this->ServiceManagement->start();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        $this->ServiceManagement->getConfiguration()->setAll($this->oldConfig);
        $this->ServiceManagement->getConfiguration()->save();
        $this->ServiceManagement->stop();
        parent::tearDown ();
    }
    
    /**
     * Tests ServiceManagement->start()
     */
    public function testStart() {
        $this->ServiceManagement->stop();
        $this->ServiceManagement->start();
        $this->assertTrue($this->ServiceManagement->has('PHPUnitTestService'));
        $this->assertTrue($this->ServiceManagement->has('PHPUnitTestServiceNotDelay'));
        $this->assertTrue($this->ServiceManagement->has('PHPUnitTestServiceNotEnabled'));
    }
    
    /**
     * Tests ServiceManagement->stop()
     */
    public function testStop() {
        $this->ServiceManagement->stop();
        $this->assertFalse($this->ServiceManagement->has('PHPUnitTestService'));
        $this->assertFalse($this->ServiceManagement->has('PHPUnitTestServiceNotDelay'));
        $this->assertFalse($this->ServiceManagement->has('PHPUnitTestServiceNotEnabled'));
    }
    
    /**
     * Tests ServiceManagement->getConfiguration()
     */
    public function testGetConfiguration() {
        $this->assertInstanceOf('\\X\\Core\\Util\\Configuration', $this->ServiceManagement->getConfiguration());
    }
    
    /**
     * Tests ServiceManagement->load()
     */
    public function testLoad() {
        $testService = array ('enable'=>false,'class' =>'\\X\\Core\\Test\\Service\\PHPUnitTestServiceLoadOutside');
        $this->ServiceManagement->load('PHPUnitTestServiceLoadOutside', $testService);
        $this->assertTrue($this->ServiceManagement->has('PHPUnitTestServiceLoadOutside'));
        
        /* 尝试加载一个不存在的服务类。 */
        try {
            $testService = array ('enable'=>false,'class' =>'non-exists');
            $this->ServiceManagement->load('non-exists', $testService);
            $this->assertTrue(false);
        } catch ( \X\Core\Service\Exception $e ) {
            $this->assertTrue(true);
        }
        
        /* 尝试加载一个非合法的服务类 */
        try {
            $testService = array ('enable'=>false,'class' =>'\\X\\Core\\Test\\Service\\PHPUnitTestServiceNotValida');
            $this->ServiceManagement->load('not-valid', $testService);
            $this->assertTrue(false);
        } catch ( \X\Core\Service\Exception $e ) {
            $this->assertTrue(true);
        }
    }
    
    /**
     * Tests ServiceManagement->get()
     */
    public function testGet() {
        /* 尝试获取一个不存在的服务 */
        try {$this->ServiceManagement->get('non-exists');
            $this->assertTrue(false);
        } catch ( \X\Core\Service\Exception $e ) {
            $this->assertTrue(true);
        }
        
        /* 尝试获取一个没有启用的服务 */
        try {$this->ServiceManagement->get('PHPUnitTestServiceNotEnabled');
        $this->assertTrue(false);
        } catch ( \X\Core\Service\Exception $e ) {
            $this->assertTrue(true);
        }
        
        $this->assertInstanceOf('\\X\\Core\\Test\\Service\\PHPUnitTestService', $this->ServiceManagement->get('PHPUnitTestService'));
        $this->assertInstanceOf('\\X\\Core\\Test\\Service\\PHPUnitTestServiceNotDelay', $this->ServiceManagement->get('PHPUnitTestServiceNotDelay'));
    }
    
    /**
     * Tests ServiceManagement->create()
     */
    public function testCreate() {
        /* 尝试创建一个已经存在的服务 */
        try {
            $this->ServiceManagement->create('PHPUnitTestService');
            $this->assertTrue(false);
        } catch ( \X\Core\Service\Exception $e ) {
            $this->assertTrue(true);
        }
        
        /* 尝试创建一个服务于一个不存在的模块 */
        try {
            $this->ServiceManagement->create('PHPUnitTestServiceNew', 'non-exists');
            $this->assertTrue(false);
        } catch ( \X\Core\Service\Exception $e ) {
            $this->assertTrue(true);
        }
        
        /* 新建一个系统的服务。 */
        $this->ServiceManagement->create('PHPUnitTestServiceNew');
        $this->assertTrue($this->ServiceManagement->getConfiguration()->has('PHPUnitTestServiceNew'));
        $this->ServiceManagement->enable('PHPUnitTestServiceNew');
        $this->ServiceManagement->load('PHPUnitTestServiceNew');
        $this->assertTrue($this->ServiceManagement->has('PHPUnitTestServiceNew'));
        $this->ServiceManagement->delete('PHPUnitTestServiceNew');
        
        /* 新建一个模块的服务。 */
        X::system()->getModuleManager()->create('PHPUnitTestModule');
        X::system()->getModuleManager()->enable('PHPUnitTestModule');
        $this->ServiceManagement->create('PHPUnitTestServiceNew', 'PHPUnitTestModule');
        $this->assertTrue($this->ServiceManagement->getConfiguration()->has('PHPUnitTestServiceNew'));
        $this->ServiceManagement->enable('PHPUnitTestServiceNew');
        $this->ServiceManagement->load('PHPUnitTestServiceNew');
        $this->assertTrue($this->ServiceManagement->has('PHPUnitTestServiceNew'));
        $this->ServiceManagement->delete('PHPUnitTestServiceNew');
        X::system()->getModuleManager()->delete('PHPUnitTestModule');
    }
    
    /**
     * Tests ServiceManagement->delete()
     */
    public function testDelete() {
        // TODO Auto-generated ServiceManagement_Test->testDelete()
        $this->markTestIncomplete ( "delete test not implemented" );
        
        $this->ServiceManagement->delete(/* parameters */);
    }
    
    /**
     * Tests ServiceManagement->has()
     */
    public function testHas() {
        // TODO Auto-generated ServiceManagement_Test->testHas()
        $this->markTestIncomplete ( "has test not implemented" );
        
        $this->ServiceManagement->has(/* parameters */);
    }
    
    /**
     * Tests ServiceManagement->getList()
     */
    public function testGetList() {
        // TODO Auto-generated ServiceManagement_Test->testGetList()
        $this->markTestIncomplete ( "getList test not implemented" );
        
        $this->ServiceManagement->getList(/* parameters */);
    }
    
    /**
     * Tests ServiceManagement->enable()
     */
    public function testEnable() {
        // TODO Auto-generated ServiceManagement_Test->testEnable()
        $this->markTestIncomplete ( "enable test not implemented" );
        
        $this->ServiceManagement->enable(/* parameters */);
    }
    
    /**
     * Tests ServiceManagement->disable()
     */
    public function testDisable() {
        // TODO Auto-generated ServiceManagement_Test->testDisable()
        $this->markTestIncomplete ( "disable test not implemented" );
        
        $this->ServiceManagement->disable(/* parameters */);
    }
    
    /**
     * Tests ServiceManagement->enableDelayStart()
     */
    public function testEnableDelayStart() {
        // TODO Auto-generated ServiceManagement_Test->testEnableDelayStart()
        $this->markTestIncomplete ( "enableDelayStart test not implemented" );
        
        $this->ServiceManagement->enableDelayStart(/* parameters */);
    }
    
    /**
     * Tests ServiceManagement->disableDelayStart()
     */
    public function testDisableDelayStart() {
        // TODO Auto-generated ServiceManagement_Test->testDisableDelayStart()
        $this->markTestIncomplete ( "disableDelayStart test not implemented" );
        
        $this->ServiceManagement->disableDelayStart(/* parameters */);
    }
}

class PHPUnitTestService extends XService {}
class PHPUnitTestServiceNotDelay extends XService {}
class PHPUnitTestServiceNotEnabled extends XService {}
class PHPUnitTestServiceLoadOutside extends XService {}
class PHPUnitTestServiceNotValida {}