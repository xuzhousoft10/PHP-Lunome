<?php
/**
 * count.php
 */
namespace X\Service\XDatabase\Core\SQL\Func;

use X\Service\XDatabase\Core\Basic;
/**
 * Func
 * 
 * Abstract class for sql functions.
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
abstract class XFunction extends Basic {
    /**
     * Convert current function object to string for using in query
     * 
     * @return string
     */
    abstract public function toString();
    
    /**
     * 
     * @return string
     */
    public function __toString() {
        return $this->toString();
    }
}