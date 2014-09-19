<?php
/**
 * delete.php
 */
namespace X\Service\XDatabase\Core\SQL\Action;

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
     * Whether use low priority on delete action. Default to false.
     * 
     * @default false
     * @var boolean
     */
    protected $lowPriority = false;
    
    /**
     * If you specify low priority, the server delays execution of the DELETE 
     * until no other clients are reading from the table. 
     * This affects only storage engines that use only table-level locking 
     * (such as MyISAM, MEMORY, and MERGE). 
     * 
     * @return Delete
     */
    public function lowPriority() {
        $this->lowPriority = true;
        return $this;
    }
    
    /**
     * Build low priority string, this method is called by toString() method.
     *
     * @return Delete
     */
    protected function getLowPriorityString() {
        if ( $this->lowPriority ) {
            $this->sqlCommand[] = 'LOW_PRIORITY';
        }
        return $this;
    }
    
    /**
     * Whether use quick model on delete action. Default to false. 
     * 
     * @default false
     * @var boolean
     */
    protected $quick = false;
    
    /**
     * For MyISAM tables, if you use the QUICK keyword, the storage engine does 
     * not merge index leaves during delete, which may speed up some kinds of
     * delete operations. 
     * 
     * @return Delete
     */
    public function quick() {
        $this->quick = true;
        return $this;
    }
    
    /**
     * Build quick model string, this method is called by toString() method.
     * 
     * @return Delete
     */
    protected function getQuickString() {
        if ( $this->quick ) {
            $this->sqlCommand[] = 'QUICK';
        }
        return $this;
    }
    
    /**
     * Whether ignore errors on delete action.
     * 
     * @default false
     * @var boolean
     */
    protected $ignore = false;
    
    /**
     * The IGNORE keyword causes MySQL to ignore all errors during the 
     * process of deleting rows. (Errors encountered during the parsing
     * stage are processed in the usual manner.) Errors that are ignored 
     * due to the use of IGNORE are returned as warnings. 
     * 
     * @uses Mysql
     * @return Delete
     */
    public function ignore() {
        $this->ignore = true;
        return $this;
    }
    
    /**
     * Build ignore model string, this method is called by toString() method.
     * 
     * @return Delete
     */
    protected function getIgnoreString() {
        if ( $this->ignore ) {
            $this->sqlCommand[] = 'IGNORE';
        }
        
        return $this;
    }
    
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
            throw new \X\Database\Exception($messages);
        }
        
        $tables = array();
        foreach ( $this->tables as $table ) {
            $tables[] = sprintf('`%s`', $table);
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
            throw new \X\Database\Exception($message);
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
            'getLowPriorityString',
            'getQuickString',
            'getIgnoreString',
            'getTableNameString',
            'getConditionString',
            'getLimitString',
            'getOrderString',
        );
    }
}