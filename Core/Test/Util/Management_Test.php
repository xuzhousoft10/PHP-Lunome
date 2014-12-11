<?php
/**
 *
 */
namespace X\Core\Test\Util;

/**
 * 
 */
use X\Core\Util\Management;

/**
 * Management test case.
 */
class Management_Test extends \PHPUnit_Framework_TestCase {
    /**
     * @var PHPUnitTestManagement
     */
    private $management = null;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->management = PHPUnitTestManagement::getManager();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        PHPUnitTestManagement::getManager()->stop();
        $this->management = null;
        parent::tearDown ();
    }
    
    /**
     * Tests Management::getManager()
     */
    public function testGetManager() {
        $this->assertInstanceOf('\\X\\Core\\Test\\Util\\PHPUnitTestManagement', $this->management);
    }
    
    /**
     * Tests Management->start()
     */
    public function testStart() {
        $this->management->start();
        $this->assertTrue($this->management->isStarted);
    }
    
    /**
     * Tests Management->stop()
     */
    public function testStop() {
        $this->management->stop();
        $this->assertFalse($this->management->isStarted);
        PHPUnitTestManagement::getManager()->start();
    }
}

class PHPUnitTestManagement extends Management {
    public $isStarted = false;
    public function start() {
        parent::start();
        $this->isStarted = true;
    }
    public function stop() {
        $this->isStarted = false;
        parent::stop();
    }
}
