<?php
/**
 * Select.php
 */
namespace X\Service\XDatabase\Core\SQL\Action;

/**
 * Update
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version 0.0.0
 * @since   0.0.0
 */
class Update extends ActionWithCondition {
    /**
     * The table reference. 
     * 
     * @var string
     */
    protected $tableReference = '';
    
    /**
     * Set Table to Update
     * 
     * @param string $table The table's name to Update
     * @return Update
     */
    public function table( $table ) {
        $this->tableReference = $table;
        return $this;
    }
    
    /**
     * Add table string into query.
     * 
     * @return Update
     */
    protected function getTableString() {
        $this->sqlCommand[] = sprintf('`%s`', $this->tableReference);
        return $this;
    }
    
    /**
     * The updated values 
     * <pre>
     *  -- key : The name of the column
     *  -- value : The value of the column
     * </pre>
     * @var array
     */
    protected $values = array();
    
    /**
     * Set column to new value for query string.
     * 
     * @param string $column The name of column to Update.
     * @param string $value The new value to column.
     * @return Update
     */
    public function set( $column, $value ) {
        $this->values[$column] = $value;
        return $this;
    }
    
    /**
     * Set values for query to Update.
     * 
     * @param array $values The values to Update.
     * @return Update
     */
    public function setValues( $values ) {
        $this->values = $values;
        return $this;
    }
    
    /**
     * Add value string into query.
     * 
     * @return Update
     */
    protected function getValueString() {
        $changes = array();
        
        foreach ( $this->values as $name => $value ) {
            $column = $this->quoteColumnName($name);
            $value = $this->quoteValue($value);
            $changes[] = sprintf('%s=%s', $column, $value);
        }
        $this->sqlCommand[] = sprintf('SET %s', implode(',', $changes));
        return $this;
    }
    
    /**
     * Add action name into query
     *
     * @return Update
     */
    protected function getActionNameString() {
        $this->sqlCommand[] = 'UPDATE';
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\SQL\Action\Base::getBuildHandlers() Base::getBuildHandlers()
     */
    protected function getBuildHandlers() {
        return array(
                'getActionNameString',
                'getTableString',
                'getValueString',
                'getConditionString',
                'getLimitString',
                'getOrderString',
        );
    }
}