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
abstract class Basic extends \X\Service\XAction\Core\Action {
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
}