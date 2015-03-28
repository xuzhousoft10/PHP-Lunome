<?php
namespace X\Util\Widget;
/**
 * 
 */
abstract class Basic {
    /**
     * @return string
     */
    abstract public function toString();
    
    /**
     * @return void
     */
    public function show() {
        echo $this->toString();
    }
}