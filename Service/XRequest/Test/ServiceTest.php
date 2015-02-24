<?php
namespace X\Service\XRequest\Test;

/**
 * 
 */
use X\Core\Util\TestCase;
use X\Service\XRequest\Service;

/**
 * 
 */
class ServiceTest extends TestCase {
    /**
     * @var \X\Service\XRequest\Service
     */
    private $service = null;
    
    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp() {
        parent::setUp();
        $this->service = Service::getService();
        $this->service->start();
    }
    
    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown() {
        $this->service->stop();
        $this->service->destroy();
        parent::tearDown();
    }
}