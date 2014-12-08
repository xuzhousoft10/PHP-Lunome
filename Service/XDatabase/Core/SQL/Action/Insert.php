<?php
/**
 * insert.php
 */
namespace X\Service\XDatabase\Core\SQL\Action;

use X\Service\XDatabase\Core\Exception;
use X\Service\XDatabase\Core\SQL\DefaultValue;
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
            throw new Exception('Table name can not be empty in insert action.');
        }
        $this->sqlCommand[] = sprintf('INTO %s', $this->quoteTableName($this->tblName));
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
            throw new Exception($message);
        }
        
        $columns = array();
        foreach ( $this->columns as $name ) {
            $columns[] = $this->quoteColumnName($name);
        }
        $columns = implode(',', $columns);
        $this->sqlCommand[] = sprintf('(%s)', $columns);
        return $this;
    }
    
    /**
     * The data would be insert into the record.
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
            throw new Exception($message);
        }
        
        if ( 0 == count($this->values) ) { return $this; }
        
        $list = array();
        foreach ( $this->values as $value ) {
            $values = array();
            foreach ( $value as $cellValue ) {
                if ( $cellValue instanceof DefaultValue ) {
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
        if ( $this->select instanceof Select ) {
            $select = $this->select->toString();
        }
        $this->sqlCommand[] = $select;
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\SQL\Action\Base::getBuildHandlers() Base::getBuildHandlers()
     */
    protected function getBuildHandlers() {
        return array(
            'getActionNameString',
            'getIntoString',
            'getColumnString',
            'getValueString',
            'getSelectString',
        );
    }
}