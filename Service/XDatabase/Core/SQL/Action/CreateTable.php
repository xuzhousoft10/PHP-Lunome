<?php
/**
 * create.table.php
 */
namespace X\Service\XDatabase\Core\SQL\Action;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Exception;

/**
 * CreateTable
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class CreateTable extends ActionAboutTable {
    /**
     * Add name part of query string.
     * 
     * @return string
     */
    protected function getNameString() {
        if ( is_null($this->name) ) {
            throw new  Exception(sprintf('Name can not be empty to create the table.', $this->name));
        }
        
        $this->sqlCommand[] = sprintf('CREATE TABLE %s', $this->quoteTableName($this->name));
        return $this;
    }
    
    /**
     * Definitions of new table columns.
     * 
     * @var array
     */
    protected $columns = null;
    
    /**
     * Set the column definitions for the table.
     * 
     * @param array $columns The definetions of each column.
     * @return CreateTable
     */
    public function columns( $columns ) {
        $this->columns = $columns;
        return $this;
    }
    
    /**
     * Add the column definination part of query.
     * 
     * @return void
     */
    protected function getColumnString() {
        $columns = array();
        foreach ( $this->columns as $column ) {
            /* @var $column \X\Service\XDatabase\Core\Table\Column */
            $columns[] = sprintf('%s %s', $this->quoteColumnName($column->getName()), $column);
        }
        $this->sqlCommand[] = sprintf('%s', implode(',', $columns));
    }
    
    /**
     * @var string
     */
    protected $primaryKey = null;
    
    /**
     * 
     * @param unknown $name
     * @return \X\Service\XDatabase\Core\SQL\Action\CreateTable
     */
    public function primaryKey( $name ) {
        $this->primaryKey = $name;
        return $this;
    }
    
    /**
     * 
     */
    protected function getPrimaryKeyString() {
        if ( null === $this->primaryKey ) {
            return;
        }
        $this->sqlCommand[] = sprintf(', PRIMARY KEY (%s)', $this->quoteColumnName($this->primaryKey));
    }
    
    /**
     * Get the handlers array, each element is the method name
     * of the subclass class 
     * 
     * @see \X\Database\SQL\Action\Base::getBuildHandlers()
     * @return array
     */
    protected function getBuildHandlers() {
        return array('getNameString','(', 'getColumnString','getPrimaryKeyString',')');
    }
}