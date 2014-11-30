<?php
/**
 * 
 */
namespace X\Util;

/**
 * 
 */
use X\Core\Basic;

/**
 * 
 */
class ArrayDataContainer extends Basic {
    /**
     * @var unknown
     */
    public $data = array();
    
    public function __construct( $data ) {
        $this->data = $data;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Basic::__get()
     */
    public function __get( $name ) {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }
    
    /**
     * @param unknown $name
     */
    public function show( $name ) {
        echo $this->$name;
    }
}