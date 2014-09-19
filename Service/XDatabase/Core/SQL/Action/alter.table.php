<?php
/**
 * alter.table.php
 */
namespace X\Service\XDB\SQL\Action;

/**
 * AlterTable
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class AlterTable extends ActionAboutTable {
    /**
     * Get alter table name part of command for query.
     * This method is called by toString() method.
     *
     * @return string
     */
    protected function getNameString() {
        if ( is_null($this->name) ) {
            throw new \X\Database\Exception(sprintf('Name can not be empty to rename the table.', $this->name));
        }
    
        $this->sqlCommand[] = sprintf('ALTER TABLE %s', $this->quoteTableName($this->name));
        return $this;
    }
    
    /**
     * This value contains the toString handler and parms to that handler.
     * 
     * @var array
     */
    protected $action = array('handler'=>null, 'parms'=>null);
    
    /**
     * Get action part of query command.
     * 
     * @return void
     */
    protected function getActionString() {
        $handler = sprintf('actionHandler%s', ucfirst($this->action['handler']));
        if ( !method_exists($this, $handler) ) {
            throw new \X\Database\Exception('Can not find handler "'.$this->action['handler'].'" for alter table.');
        }
        $this->$handler();
    }
    
    /**
     * Set alter table action to add column.
     * 
     * @param string $name The name of the column.
     * @param string $definition The definition of the column.
     * @return AlterTable
     */
    public function addColumn( $name, $definition ) {
        $this->action['handler'] = 'addColumn';
        $this->action['parms'] = array('name'=>$name, 'definition'=>$definition);
        return $this;
    }
    
    /**
     * Get the action handler string of add column to query command.
     * 
     * @return void
     */
    protected function actionHandlerAddColumn() {
        $this->sqlCommand[] = sprintf(
            'ADD %s %s', 
            $this->quoteColumnName($this->action['parms']['name']),
            $this->action['parms']['definition']);
    }
    
    /**
     * Set alter table action to "add index"
     * 
     * @param string $name The name of the index.
     * @param array $columns The column list that index contains.
     * @return AlterTable
     */
    public function addIndex( $name, $columns ) {
        $this->action['handler'] = 'addIndex';
        $this->action['parms'] = array('name'=>$name, 'columns'=>$columns);
        return $this;
    }
    
    /**
     * Add the action handler string of add index to query command.
     * 
     * @return void
     */
    protected function actionHandlerAddIndex() {
        $this->sqlCommand[] = sprintf('ADD INDEX `%s` (%s)',
            $this->action['parms']['name'],
            implode(',', $this->quoteColumnNames($this->action['parms']['columns'])));
    }
    
    /**
     * Set alter table action to "add primary key"
     * 
     * @param array|string $columns The column names to be primary key
     * @return AlterTable
     */
    public function addPrimaryKey( $columns ) {
        $this->action['handler'] = 'addPrimaryKey';
        $this->action['parms'] = array('columns' => $columns);
        return $this;
    }
    
    /**
     * Add the action handler string of add primary key to query command.
     * 
     * @return void
     */
    protected function actionHandlerAddPrimaryKey() {
        $columns = $this->action['parms']['columns'];
        $columns = is_array($columns) ? $columns : array($columns);
        $columns = $this->quoteColumnNames($columns);
        $columns = implode(',', $columns);
        $this->sqlCommand[] = sprintf('ADD PRIMARY KEY (%s)', $columns);
    }
    
    /**
     * Set alter table action to "add unique"
     * 
     * @param array|string $columns The columns that unique contains.
     * @return AlterTable
     */
    public function addUnique( $columns ) {
        $this->action['handler'] = 'addUnique';
        $this->action['parms'] = array('columns' => $columns);
        return $this;
    }
    
    /**
     * Add the action handler string of "add unique" to query command.
     * 
     * @return void
     */
    protected function actionHandlerAddUnique() {
        $columns = $this->action['parms']['columns'];
        $columns = is_array($columns) ? $columns : array($columns);
        $columns = $this->quoteColumnNames($columns);
        $columns = implode(',', $columns);
        $this->sqlCommand[] = sprintf('ADD UNIQUE ( %s )',$columns);
    }
    
    /**
     * Set alter table action to "change column"
     * 
     * @param string $column The name of column to change
     * @param string $newName The new name of the column
     * @param string $definition The definition of the column
     * @return AlterTable
     */
    public function changeColumn( $column, $newName, $definition='' ) {
        $this->action['handler'] = 'changeColumn';
        $this->action['parms'] = array('column'=>$column, 'newName'=>$newName, 'definition'=>$definition);
        return $this;
    }
    
    /**
     * Add the action handler string of "change column" to query command.
     * 
     * @return void
     */
    protected function actionHandlerChangeColumn() {
        $this->sqlCommand[] = sprintf('CHANGE COLUMN %s %s %s', 
            $this->quoteColumnName($this->action['parms']['column']),
            $this->quoteColumnName($this->action['parms']['newName']),
            $this->action['parms']['definition']);
    }
    
    /**
     * Set alter table action to "drop column"
     * 
     * @param string $name The name of column to drop
     * @return AlterTable
     */
    public function dropColumn( $name ) {
        $this->action['handler'] = 'dropColumn';
        $this->action['parms']['name'] = $name;
        return $this;
    }
    
    /**
     * Add the action handler string of "drop column" to query command.
     * 
     * @return void
     */
    protected function actionHandlerDropColumn() {
        $this->sqlCommand[] = sprintf('DROP COLUMN `%s`', $this->action['parms']['name']);
    }
    
    /**
     * Set alter table action to "drop primary key"
     * 
     * @return AlterTable
     */
    public function dropPrimaryKey() {
        $this->action['handler'] = 'dropPrimaryKey';
        return $this;
    }
    
    /**
     * Add the action handler string of "drop column" to query command.
     * 
     * @return void
     */
    protected function actionHandlerDropPrimaryKey() {
        $this->sqlCommand[] = 'DROP PRIMARY KEY';
    }
    
    /**
     * Set alter table action to "drop index"
     * 
     * @param string $name The name of index to drop
     * @return AlterTable
     */
    public function dropIndex( $name ) {
        $this->action['handler'] = 'dropIndex';
        $this->action['parms']['name'] = $name;
        return $this;
    }
    
    /**
     * Add the action handler string of "drop index" to query command.
     * 
     * @return void
     */
    protected function actionHandlerDropIndex() {
        $this->sqlCommand[] = sprintf('DROP INDEX %s', $this->action['parms']['name']);
    }
    
    /**
     * Get the name list of handlers to build the query string
     * 
     * @see \X\Database\SQL\Action\Base::getBuildHandlers()
     * @return array
     */
    protected function getBuildHandlers() {
        return array('getNameString', 'getActionString');
    }
}