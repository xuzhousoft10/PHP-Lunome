<?php
namespace X\Core\Util\TestCase;

/**
 * 
 */
use X\Core\X;

/**
 * 
 */
abstract class ServiceTestCase extends ManagerTestCase {
    /**
     * @var mixed
     */
    private $oldServiceConfig = null;
    
    /**
     * @return string
     */
    abstract protected function getServiceClass();
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\TestCase\ManagerTestCase::getManager()
     */
    protected function getManager() {
        $class = $this->getServiceClass();
        $name = $class::getServiceName();
        if ( X::system()->getServiceManager()->has($name) ) {
            $this->oldServiceConfig = X::system()->getServiceManager()->getConfiguration()->get($name);
        } else {
            X::system()->getServiceManager()->register($class);
            $this->oldServiceConfig = false;
        }
        
        return X::system()->getServiceManager()->get($name);
    }
    
    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown() {
        $class = $this->getServiceClass();
        $name = $class::getServiceName();
        
        if ( false === $this->oldServiceConfig ) {
            X::system()->getServiceManager()->unregister($name);
            $this->oldServiceConfig = null;
        } else {
            X::system()->getServiceManager()->getConfiguration()->set($name, $this->oldServiceConfig);
            X::system()->getServiceManager()->getConfiguration()->save();
        }
        
        parent::tearDown();
    }
}