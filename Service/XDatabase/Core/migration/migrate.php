<?php
/**
 * migrate.php
 */
namespace X\Database\Migration;
/**
 * Migrate
 * 
 * @author  Michael Luthor <michael.the.ranidae@gamil.com>
 * @since   0.0.0
 * @version 0.0.0
 */
abstract class Migrate extends \X\Database\Base {
    /**
     * This method would be execute when upgrade the database.
     * so you can overwrite this method to do update.
     * 
     * @return void
     */
    abstract public function up();
    
    /**
     * This method would be execute when degrade the database.
     * so you can overwrite this method to do dedate.
     * 
     * @return void
     */
    abstract public function down();
    
    /**
     * Open an database table for operation.
     * 
     * @param string $name The name of the table to open
     * @return \X\Database\Table\Management
     */
    protected function openTable( $name ) {
        return \X\Database\Table\Management::open($name);
    }
    
    /**
     * Create a new table by given name and column definations.
     * 
     * @param string $name The name of table to crate.
     * @param array $columns The column definations for new table.
     * @return Migrate
     */
    protected function createTable($name, $columns) {
        \X\Database\Table\Management::create($name, $columns);
        return $this;
    }
    
    /**
     * Drop the table in database by given name.
     * 
     * @param string $name The name of table to drop.
     * @return Migrate
     */
    protected function dropTable( $name ){
        $this->openTable($name)->drop();
        return $this;
    }
    
    /**
     * Rename the table from old name to new name.
     * 
     * @param string $oldName The name of the table to rename.
     * @param string $newName The new name to that table.
     * @return Migrate
     */
    protected function renameTable( $oldName, $newName ){
        $this->openTable($oldName)->rename($newName);
        return $this;
    }
    
    /**
     * Add a new column into given table.
     * 
     * @param string $table The name of the table to add into.
     * @param string $column The name of the column to add.
     * @param string $definition The definition of the column.
     * @return Migrate
     */
    protected function addColumn( $table, $column, $definition ){
        $this->openTable($table)->addColumn($column, $definition);
        return $this;
    }
    
    /**
     * Drop the column from given table.
     * 
     * @param string $table The name of the table to drop column.
     * @param string $column The name of column to drop.
     * @return Migrate
     */
    protected function dropColumn( $table, $column ){
        $this->openTable($table)->dropColumn($column);
        return $this;
    }
    
    /**
     * Change the definition of a column in given table.
     * 
     * @param string $table The name of the table contains the column.
     * @param string $column The name of column to modified.
     * @param string $definition The new definition for that column. 
     * @return Migrate
     */
    protected function changeColumn( $table, $column, $definition ){
        $this->openTable($table)->changeColumn($column, $definition);
        return $this;
    }
    
    /**
     * Insert a new record into given table.
     * 
     * @param string $table The name of table to insert into.
     * @param string $values The value of the record to insert.
     * @return Migrate
     */
    protected function insert( $table, $values ){
        $this->openTable($table)->insert($values);
        return $this;
    }
    
    /**
     * Truncate the table data by given table.
     * 
     * @param string $table The name of the table to truncate.
     * @return Migrate
     */
    protected function truncate( $table ){
        $this->openTable($table)->truncate();
        return $this;
    }
    
    /**
     * Add index into given table.
     * 
     * @param string $table The name of table to add index.
     * @param string $index The name of index to add.
     * @param array $columns The column name list that index contains.
     * @return Migrate
     */
    protected function addIndex( $table, $index, $columns ){
        $this->openTable($table)->addIndex($index, $columns);
        return $this;
    }
    
    /**
     * Drop the index from given table.
     * 
     * @param string $table The name of table to drop index.
     * @param string $index The name of index to drop.
     * @return Migrate
     */
    protected function dropIndex($table, $index){
        $this->openTable($table)->dropIndex($index);
        return $this;
    }
    
    /**
     * Set primary key to given table.
     * 
     * @param string $table The name of table to set primary key.
     * @param string $columns The name of column to set as primary key.
     * @return Migrate
     */
    protected function setPrimaryKey($table, $columns){
        $this->openTable($table)->addPrimaryKey($columns);
        return $this;
    }
    
    /**
     * Drop the primary key from given table.
     * 
     * @param string $table The name of table to drop primary key.
     * @return Migrate
     */
    protected function dropPrimaryKey( $table ){
        $this->openTable($table)->dropPrimaryKey();
        return $this;
    }
}