<?php
/**
 * This file define a basic action class.
 */
namespace X\Util\Action;

/**
 * Use statements
 */
use X\Core\X;

/**
 * The basic action class
 * 
 * @author Michael Luthor <michaelluthor@163.com>
 */
abstract class Basic extends \X\Service\XAction\Core\Handler\WebAction {
    /**
     * The activated module instance.
     *
     * @var \X\Core\Module\XModule
     */
    private $module = null;
    
    /**
     * Get the module that this action belongs to.
     *
     * @return \X\Core\Module\XModule
     */
    public function getModule() {
        if ( is_null($this->module) ) {
            $this->module = X::system()->getModuleManager()->get($this->getGroup());
        }
        return $this->module;
    }
    
    /**
     * @return string
     */
    public function getAssetsURL () {
        return X::system()->getConfiguration()->get('assets-base-url');
    }
    
    public function __call( $name, $parms ) {
        if ( 'get' === substr($name, 0, 3) && 'Service' === substr($name, strlen($name)-7)){
            $serviceName = substr($name, 3);
            $serviceName = substr($serviceName, 0, strlen($serviceName)-7);
            return $this->getService($serviceName);
        } else {
            ;// nothing
        }
    }
    
    protected function getService( $name ) {
        return X::system()->getServiceManager()->get($name);
    }
}