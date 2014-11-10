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
class Count extends XFunction {
    /**
     * The column name to count
     * 
     * @var string
     */
    protected $column = '*';
    
    /**
     * Initiate the count object by given column name.
     * 
     * @param string $column The column name to count
     */
    public function __construct( $column='*' ) {
        $this->column = $column;
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\SQL\Func\Func::toString() Func::toString()
     */
    public function toString() {
        return sprintf('COUNT(%s)', $this->quoteColumn($this->column));
    }
    
    /**
     * @return string
     */
    protected function quoteColumn() {
        if ( '*' === $this->column ) {
            return '*';
        } else {
            $column = explode('.', $this->column);
            foreach ( $column as $index => $name ) {
                $column[$index] = sprintf('`%s`', $name);
            }
            $column = implode('.', $column);
            return $column;
        }
    }
}