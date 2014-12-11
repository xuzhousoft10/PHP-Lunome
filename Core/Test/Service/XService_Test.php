<?php
/**
 *
 */
namespace X\Core\Test\Service;

/**
 * 
 */
use X\Core\Service\XService;

/**
 * XService test case.
 */
class XService_Test extends \PHPUnit_Framework_TestCase {
    /**
     * @var PHPUnitTestServiceOnly
     */
    private $service = null;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->service = PHPUnitTestServiceOnly::getService();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        $this->service = null;
        parent::tearDown ();
    }
    
    /**
     * Tests XService::getService()
     */
    public function testGetService() {
        $this->assertInstanceOf('\\X\\Core\\Test\\Service\\PHPUnitTestServiceOnly', PHPUnitTestServiceOnly::getService());
    }
    
    /**
     * Tests XService::getServiceName()
     */
    public function testGetServiceName() {
        $this->assertSame('', PHPUnitTestServiceOnly::getServiceName());
    }
    
    /**
     * Tests XService->getName()
     */
    public function testGetName() {
        $this->assertSame('', $this->service->getName());
    }
    
    /**
     * Tests XService->start()
     */
    public function testStart() {
        $this->service->start();
        $this->assertSame('running', $this->service->status);
    }
    
    /**
     * Tests XService->stop()
     */
    public function testStop() {
        $this->service->stop();
        $this->assertSame('stopped', $this->service->status);
    }
    
    /**
     * Tests XService->getPath()
     */
    public function testGetPath() {
        $this->assertSame(dirname(__FILE__), $this->service->getPath());
        $this->assertSame(dirname(__FILE__).DIRECTORY_SEPARATOR.'test', $this->service->getPath('test'));
    }
    
    /**
     * Tests XService->getConfiguration()
     */
    public function testGetConfiguration() {
        $this->assertInstanceOf('\\X\\Core\\Util\\Configuration', $this->service->getConfiguration());
    }
}

class PHPUnitTestServiceOnly extends XService {
    public $status = null;
    protected function afterStart() {
        $this->status = 'running';
    }
    protected function afterStop() {
        $this->status = 'stopped';
    }
}

