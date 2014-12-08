<?php
/**
 * delete.php
 */
namespace X\Service\XDatabase\Core\SQL\Action;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Exception as DBException;

/**
 * Delete
 * 
 * <pre>
 * $delete->from('table');
 * $delete->from('table', 'table1');
 * $delete->from(array('table', 'table1'));
 * $delete->from('table')->where($condition);
 * $delete->from('table')->where(array('deleted_at'=>null));
 * $delete->from('table')->where('deleted_at != null');
 * $delete->from('table')->limit(1);
 * $delete->from('table')->orderBy('name', 'ASC');
 * $delete->from('table')->orderBy(array('name','ASC'));
 * $delete->from('table')->orderBy(array('name','ASC'), array('name','ASC'));
 * $delete->from('table')->lowPriority();
 * $delete->from('table')->ignore();
 * $delete->from('table')->quick();
 * </pre>
 * 
 * @author  Michael Luthor <michael.the.ranidae@gamil.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Delete extends ActionWithCondition {
    /**
     * The table names that will delete from.
     * 
     * @var string[]
     */
    protected $tables = array();
    
    /**
     * Set the table name that delete from.
     * 
     * @param string|array $table The table name that delte from
     * @param string $_
     * @return Delete
     */
    public function from( $table ) {
        if ( !is_array($table) ) {
            $table = func_get_args();
        }
        $this->tables = array_merge($this->tables, $table);
        return $this;
    }
    
    /**
     * Build from string, this method is called by toString() method.
     * 
     * @return string
     */
    protected function getTableNameString() {
        if ( 0 == count($this->tables) ) {
            $messages = 'Delete action requires at least one table to delete from.';
            throw new DBException($messages);
        }
        $tables = array();
        foreach ( $this->tables as $table ) {
            $tables[] = $this->quoteTableName($table);
        }
        $this->sqlCommand[] = sprintf('FROM %s', implode(',', $tables));
        return $this;
    }
    
    /**
     * Add action's name into query.
     * 
     * @return Delete
     */
    protected function getActionNameString() {
        $this->sqlCommand[] = 'DELETE';
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\SQL\Action\ActionWithCondition::getOrderString() parent::getOrderString()
     */
    protected function getOrderString() {
        if ( $this->hasOrder() && 0 < count($this->tables)) {
            $message =  'Delete action does not support "order by" '.
                        'when delete from multi tables.';
            throw new DBException($message);
        }
        
        parent::getOrderString();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\SQL\Action\Base::getBuildHandlers()  parent::getBuildHandlers()
     */
    protected function getBuildHandlers() {
        return array(
            'getActionNameString',
            'getTableNameString',
            'getConditionString',
            'getLimitString',
            'getOrderString',
        );
    }
}