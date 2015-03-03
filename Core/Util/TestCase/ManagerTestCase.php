<?php
namespace X\Core\Util\TestCase;

/**
 * 
 */
use X\Core\Util\TestCase;
use X\Core\Util\Manager;

/**
 * 
 */
abstract class ManagerTestCase extends TestCase {
    /**
     * @var Manager
     */
    protected $manager = null;
    
    /**
     * @var array
     */
    private $oldConfiguration = null;
    
    /**
     * @return Manager
     */
    abstract protected function getManager();
    
    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp() {
        parent::setUp();
        $this->manager = $this->getManager();
        $this->oldConfiguration = $this->manager->getConfiguration()->toArray();
        $this->cleanTheConfiguration($this->manager);
        $this->manager->start();
    }
    
    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown() {
        $this->cleanTheConfiguration($this->manager);
        $this->manager->getConfiguration()->merge($this->oldConfiguration);
        $this->manager->getConfiguration()->save();
        $this->manager->stop();
        $this->manager->destroy();
        $this->manager = null;
        parent::tearDown();
    }
    
    /**
     * @param Manager $manager
     */
    protected function cleanTheConfiguration( $manager ) {
        $configuration = $manager->getConfiguration()->toArray();
        foreach ( $configuration as $key => $config ) {
            $manager->getConfiguration()->remove($key);
        }
        $manager->getConfiguration()->save();
    }
}