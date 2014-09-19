<?php
/**
 * Namespace defination
 */
namespace X\Service\XSession\Core;

/**
 * The abstract class for session handler driver.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
abstract class HandlerDriverBasic implements InterfaceHandler {
    /**
     * Whether there is error on this driver during the running
     * 
     * @var boolean
     */
    protected $hasError = false;
    
    /**
     * Check the error on this driver.
     * 
     * @return boolean
     */
    public function hasError() {
        return $this->hasError;
    }
    
    /**
     * Check the configuration values.
     * 
     * @param array $config The configurations.
     * @return boolean
     */
    public function checkConfig($config) { return true; }
}