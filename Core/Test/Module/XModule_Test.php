<?php
/**
 *
 */
namespace X\Core\Test\Module;

/**
 * 
 */
use X\Core\Module\XModule;

/**
 * XModule test case.
 */
class XModule_Test extends \PHPUnit_Framework_TestCase {
    /**
     * @var PHPUnitTestModule
     */
    private $XModule;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->XModule = new PHPUnitTestModule('test');
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        $this->XModule = null;
        parent::tearDown ();
    }
    
    /**
     * Tests XModule->getName()
     */
    public function testGetName() {
        $this->assertSame('test', $this->XModule->getName());
    }
    
    /**
     * Tests XModule->__construct()
     */
    public function test__construct() {
        $this->assertSame('inited', $this->XModule->status);
    }
    
    /**
     * Tests XModule->getPath()
     */
    public function testGetPath() {
        $this->assertSame(dirname(__FILE__), $this->XModule->getPath());
        $this->assertSame(dirname(__FILE__).DIRECTORY_SEPARATOR.'test', $this->XModule->getPath('test'));
    }
}

class PHPUnitTestModule extends XModule {
    public $status = null;
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::init()
     */
    protected function init() {
        $this->status = 'inited';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::run()
     */
    public function run($parameters = array()) {
        $this->status = 'running';
    }
}