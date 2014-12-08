<?php
/**
 * rand.php
 */
namespace X\Service\XDatabase\Core\SQL\Func;

/**
 * Rand
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Rand extends XFunction {
    /**
     * (non-PHPdoc)
     * @see \X\Database\SQL\Func\Func::toString() Func::toString()
     */
    public function toString() {
        return 'RAND()';
    }
}