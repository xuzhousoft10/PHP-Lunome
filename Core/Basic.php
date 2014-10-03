<?php
/**
 * Namespace defination
 */
namespace X\Core;

/**
 * The basic class for the whole framework. 
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class Basic extends \stdClass {
    /**
     * The magic getter for this class.
     * 
     * @param string $name The name of attribute to get.
     * @throws Exception
     * @return mixed
     */
    public function __get( $name ) {
        $getter = sprintf('get%s', ucfirst($name));
        if ( !method_exists($this, $getter) ) {
            throw new Exception(sprintf('"%s" does not exists or unable to read.', $name));
        }
        
        return $this->$getter();
    }
    
    /**
     * The magic setter for this class.
     * 
     * @param string $name The name of attribute to set.
     * @param mixed $value The value to set to the attribute.
     * @throws Exception
     * @return void
     */
    public function __set( $name, $value ) {
        $setter = sprintf('set%s', ucfirst($name));
        if ( method_exists($this, $setter) ) {
            throw new Exception(sprintf('"%s" does not exists or unable to write.', $name));
        }
        
        $this->$setter($value);
    }
    
    /**
     * 
     * @return string
     */
    public function __toString() {
        return $this->toString();
    }
    
    /**
     * 
     * @return string
     */
    public function toString() {
        return __CLASS__;
    }
}