<?php
/**
 * count.php
 */
namespace X\Service\XDatabase\Core\SQL\Func;

/**
 * Count
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Max extends XFunction {
    /**
     * The column name to count
     * 
     * @var string
     */
    protected $column = null;
    
    /**
     * Initiate the count object by given column name.
     * 
     * @param string $column The column name to count
     */
    public function __construct( $column ) {
        $this->column = $column;
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\SQL\Func\Func::toString() Func::toString()
     */
    public function toString() {
        return "MAX({$this->column})";
    }
}