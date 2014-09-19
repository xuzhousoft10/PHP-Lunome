<?php
/**
 * insert.php
 */
namespace X\Service\XDatabase\Core\SQL\Action;

/**
 * Insert
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Insert extends Basic {
    /**
     * Add action name into string.
     * 
     * @return Insert
     */
    protected function getActionNameString() {
        $this->sqlCommand[] = 'INSERT';
        return $this;
    }
    
    /**
     * The name of the table which would insert into. It can not be empty.
     * 
     * @var string
     */
    protected $tblName = null;
    
    /**
     * Set the table name which would insert data into.
     * 
     * @param stirng $table The name table to insert into
     * @return Insert
     */
    public function into( $table ){
        $this->tblName = $table;
        return $this;
    }
    
    /**
     * Get sql command part of insert table name. The method is called by
     * toString() method to build the whole command.
     * 
     * @return Insert
     */
    protected function getIntoString() {
        if ( is_null($this->tblName) ) {
            throw new \X\Database\Exception('Table name can not be empty in insert action.');
        }
        
        $this->sqlCommand[] = sprintf('INTO `%s`', $this->tblName);
        return $this;
    }
    
    /**
     * The column name lists would be insert. It could be empty here.
     * 
     * @var string[]
     */
    protected $columns = array();
    
    /**
     * Set the colum name list manualy, You can call this method with
     * over one parms, so, each parm should be the name of the column.
     * 
     * <pre>
     * $insert->into('table')->columns('col1', 'col2')->values('val1', 'val2');
     * </pre>
     * @param string $name The name of column to match values
     * @param string $_
     * @return Insert
     */
    public function columns( $name ) {
        $this->columns = func_get_args();
        return $this;
    }
    
    /**
     * Get sql command part of column list. The method is called by
     * toString() method to build the whole command.
     * 
     * @return Insert
     */
    protected function getColumnString() {
        if ( 0 == count($this->columns) ) return $this;
        
        if ( isset($this->values[0]) && count($this->values[0]) != count($this->columns) ) {
            $message = 'The value to insert does not match the column.';
            throw new \X\Database\Exception($message);
        }
        
        $columns = array();
        
        foreach ( $this->columns as $name ) {
            $columns[] = sprintf('`%s`', $name);
        }
        $columns = implode(',', $columns);
        $this->sqlCommand[] = sprintf('(%s)', $columns);
        return $this;
    }
    
    /**
     * The data would be insert into the record.
     * 
     * @var array
     */
    protected $values = array();
    
    /**
     * Set the data would be insert into the record, you can call this method 
     * with over one parms, and if you do so, each parms would be the value 
     * of each columns. Also, you can call it with an array, and if the array's 
     * key is not numbers, then the keys would be the columns names to insert. 
     * You can call this method over one time, so that you can insert over one 
     * record in one command.
     * 
     * <pre>
     * $insert->into('table')->values(array('col1'=>'val1', 'col2'=>'val2'));
     * $insert->into('table')->values($values)->values($values);
     * $insert->into('table')->values(array('col1'=>new SQLBuilderDefaultValue(), 'col2'=>'val2'));
     * </pre>
     * 
     * @param array|mixed $values The value to insert into.
     * @param mixed $_
     * @return Insert
     */
    public function values( $values ) {
        $argCount = func_num_args();
        if ( 1 == $argCount &&  $values instanceof \Iterator ) {
            $vals = array();
            foreach ( $values as $name => $val ) {
                $this->columns[] = $name;
                $vals[] = $val;
            }
            $values = $vals;
        }
        else if ( 1 == $argCount && is_array($values) && !isset($values[0]) ) {
            $this->columns = array_keys($values);
            $values = array_values($values);
        }
        else if ( 1 == $argCount && is_array($values) && isset($values[0]) ) {
            // Nothing
        }
        else {
            $values = func_get_args();
        }
        $this->values[] = $values;
        return $this;
    }
    
    /**
     * Get the command part of sql string. this method would be called by 
     * toString() method.
     * 
     * @return Insert
     */
    protected function getValueString() {
        if ( 0 == count($this->values) && is_null($this->select)) {
            $message = 'Can not insert empty record into table.';
            throw new \X\Database\Exception($message);
        }
        
        if ( 0 == count($this->values) ) { return $this; }
        
        $list = array();
        foreach ( $this->values as $value ) {
            $values = array();
            foreach ( $value as $cellValue ) {
                if ( $cellValue instanceof \X\Database\SQL\Other\DefaultValue ) {
                    $values[] = 'DEFAULT';
                }
                else {
                    $values[] = $this->quoteValue($cellValue);
                }
            }
            $list[] = sprintf('(%s)', implode(',', $values));
        }
        $this->sqlCommand[] = sprintf('VALUES %s', implode(',', $list));
        return $this;
    }
    
    /**
     * This value contains the select part in insert action. It could be a 
     * SQLBuilderActionSelect object or a select command string.
     * 
     * @var Select|string
     */
    protected $select = null;
    
    /**
     * Set the select part in insert command.
     * 
     * <pre>
     * $insert->into('table')->select( SQLBuilderActionSelect $select );
     * $insert->into('table')->select( 'SELECT * FROM `table2`' );
     * </pre>
     * 
     * @param Select|string $select The select query
     * @return Insert
     */
    public function select( $select ) {
        $this->select = $select;
        return $this;
    }
    
    /**
     * Get the select part of insert command. this method is called by 
     * toString() method.
     * 
     * @return Insert
     */
    protected function getSelectString() {
        if ( is_null($this->select) ) return $this;
        
        $select = $this->select;
        if ( $this->select instanceof \X\Database\SQL\Action\Select ) {
            $select = $this->select->toString();
        }
        $this->sqlCommand[] = $select;
        return $this;
    }
    
    /**
     * An array contains all hanslers when key is duplicated.
     * 
     * @var array
     */
    protected $onDuplicateKeyUpdateHandlers = array();
    
    /**
     * Set the handlers when unquie column is duplicated. you can call 
     * this method with over one parms to set multi handlers or call
     * it over one times to do the same thing.
     * 
     * <pre>
     * $insert->into('table')->onDuplicateKeyUpdate('col1 = col1+1', 'col2 = col2+1');
     * </pre>
     * 
     * @param mixed $handler The action on duplicated key exists.
     * @param mixed $_
     * @return Insert
     */
    public function onDuplicateKeyUpdate( $handler ) {
        $this->onDuplicateKeyUpdateHandlers = array_merge($this->onDuplicateKeyUpdateHandlers, func_get_args());
        return $this;
    }
    
    /**
     * Get the command part of sql string, this method is call by
     * toString() method.
     * 
     * @return Insert
     */
    protected function getOnDuplicateKeyUpdate() {
        if( 0 == count($this->onDuplicateKeyUpdateHandlers) ) return $this;
        
        $handlers = implode(',', $this->onDuplicateKeyUpdateHandlers);
        $this->sqlCommand[] = sprintf('ON DUPLICATE KEY UPDATE %s', $handlers);
        return $this;
    }
    
    /**
     * Wether ignore the data when duplicate key exists during insert.
     * 
     * @var boolean
     */
    protected $ignoreOnDuplicateKey = false;
    
    /**
     * Set wether ignore the data when duplicate key exists during insert.
     * <pre>
     * $insert->into('table')->values($values)->ignoreOnDuplicateKey();
     * </pre>
     * @return Insert
     */
    public function ignoreOnDuplicateKey( ) {
        $this->ignoreOnDuplicateKey = true;
        return $this;
    }
    
    /**
     * Get ignore part of insert command, this method is called by toString()
     * method.
     * 
     * @return Insert
     */
    protected function getIgnoreOnDuplicateKeyString() {
        if ( $this->ignoreOnDuplicateKey ) {
            $this->sqlCommand[] = 'IGNORE';
        }
        return $this;
    }
    
    /**
     * This value contains the priority of the insertelation.
     * 
     * @var string
     */
    protected $priority = null;
    
    /**
     * The priority name of low priority.
     * 
     * @var string
     */
    const PRIORITY_LOW      = 'LOW_PRIORITY';
    
    /**
     * The priority name of delayed.
     * 
     * @var string
     */
    const PRIORITY_DELAYED  = 'DELAYED';
    
    /**
     * The priority name of high priority.
     * 
     * @var string
     */
    const PRIORITY_HIGH     = 'HIGH_PRIORITY';
    
    /**
     * Set the value of priority of the insertelation.
     * <pre>
     * $insert->into('table')->value($value)->priority(SQLBuilderActionInsert::PRIORITY_LOW)
     * </pre>
     * @param string $priority The name of priority.
     * @return Insert
     */
    public function priority( $priority ) {
        $this->priority = $priority;
        return $this;
    }
    
    /**
     * Get the proority part of the command, this method is called by 
     * toString() method.
     * 
     * @return Insert
     */
    protected function getPriorityString() {
        if ( !is_null($this->priority) ) {
            $this->sqlCommand[] = $this->priority;
        }
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\SQL\Action\Base::getBuildHandlers() Base::getBuildHandlers()
     */
    protected function getBuildHandlers() {
        return array(
            'getActionNameString',
            'getPriorityString', 
            'getIgnoreOnDuplicateKeyString',
            'getIntoString',
            'getColumnString',
            'getValueString',
            'getSelectString',
            'getOnDuplicateKeyUpdate',
        );
    }
}