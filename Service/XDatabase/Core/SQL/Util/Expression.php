<?php
/**
 * This file implements the exoression for sql.
 */
namespace X\Service\XDatabase\Core\SQL\Util;

/**
 * The expression class.
 */
class Expression {
    /**
     * @var string
     */
    private $expression = null;
    
    /**
     * @param unknown $expression
     */
    public function __construct($expression) {
        $this->expression = $expression;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Basic::toString()
     */
    public function toString() {
        return $this->expression;
    }
}