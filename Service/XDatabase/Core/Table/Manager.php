<?php
/**
 * table.php
 */
namespace X\Service\XDatabase\Core\Table;
use X\Core\X;
use X\Service\XDatabase\Core\Basic;
use X\Service\XDatabase\XDatabaseService;
use X\Service\XDatabase\Core\Exception;
use X\Service\XDatabase\Core\SQL\Builder as SQLBuilder;

/**
 * Table
 *
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Manager extends Basic {
    /**
     * Create a new table.
     * 
     * @param string $name The name of table to create
     * @param array $columns The column definitions.
     * @return Management
     */
    public static function create( $name, $columns ) {
        $sql = SQLBuilder::build()->createTable()
            ->name($name)
            ->columns($columns)
            ->toString();
        
        if ( !self::executeSQLQueryWithOutResult($sql) ) {
            return false;
        }
        return new Management($name);
    }
    
    /**
     * executeSQLQueryWithOutResult
     * 
     * @param string $query The query to execute
     * @return boolean
     */
    protected static function executeSQLQueryWithOutResult( $query ) {
        return X::system()->getServiceManager()->get('XDb')->getDb()->exec($query);
    }
    
    /**
     * Get a new table object by name.
     * 
     * @param string $name The name of table to open for operation.
     * @return Manager
     */
    public static function open( $name ) {
        return new Manager($name);
    }
    
    /**
     * The name of the target table.
     * 
     * @var string
     */
    protected $name = null;
    
    /**
     * Initiate the object by given table name
     * 
     * @param string $name The name of table to operate.
     */
    protected function __construct( $name ) {
        $this->name = $name;
    } 
    
    /**
     * Drop the operating table.
     * 
     * @return boolean
     */
    public function drop() {
        $sql = SQLBuilder::build()->dropTable()
            ->name($this->name)
            ->toString();
        return self::executeSQLQueryWithOutResult($sql);
    }
    
    /**
     * Truncate the operating table.
     * 
     * @return Management|boolean
     */
    public function truncate() {
        $query = SQLBuilder::build()->truncate()
            ->name($this->name)
            ->toString();
        return self::executeSQLQueryWithOutResult($query) ? $this : false;
    }
    
    /**
     * Insert a new record into the operating table.
     * 
     * @param array $values The value of new record.
     * @return Management|boolean
     */
    public function insert( $values ) {
        $query = SQLBuilder::build()->insert()
            ->into($this->name)->values($values)->toString();
        return self::executeSQLQueryWithOutResult($query) ? $this : false;
    }
    
    /**
     * Rename the operating table.
     * 
     * @param string $name The new name for operating table.
     * @return Management|boolean
     */
    public function rename( $name ) {
        $query = SQLBuilder::build()->rename()
            ->name($this->name)
            ->newName($name)
            ->toString();
        $isRenamed = self::executeSQLQueryWithOutResult($query);
        if ( $isRenamed ) {
            $this->name = $name;
        }
        return $isRenamed ? $this : false;
    }
    
    /**
     * Add new column to the operating table.
     * 
     * @param string $name The name of column to add
     * @param string $definition The definition of column
     * @return Management|boolean
     */
    public function addColumn( $name, $definition ) {
        return $this->doAlterAction('addColumn', array($name, $definition));
    }
    
    /**
     * Drop column from the operating table.
     * 
     * @param string $name The name of column to drop
     * @return Management|boolean
     */
    public function dropColumn( $name ){
        return $this->doAlterAction('dropColumn', array($name));
    }
    
    /**
     * Rename column from the operating table.
     * 
     * @param string $oldName The old name of column to rename
     * @param string $newName The new name of column
     * @return Management|boolean
     */
    public function renameColumn( $oldName, $newName ){
        return $this->doAlterAction('changeColumn', array($oldName, $newName));
    }
    
    /**
     * Change column from the operating table.
     * 
     * @param string $name The name of column to change
     * @param string $definition The new definition for column
     * @return Management|boolean
     */
    public function changeColumn($name, $definition){
        return $this->doAlterAction('changeColumn', array($name, $name, $definition));
    }
    
    /**
     * Add index for the operating table.
     * 
     * @param string $name The name of index to add.
     * @param array $columns The column name list that index contains
     * @return Management|boolean
     */
    public function addIndex( $name, $columns ) {
        return $this->doAlterAction('addIndex', array($name, $columns));
    }
    
    /**
     * Drop index from the operating table.
     * 
     * @param string $name The name index to drop
     * @return Management|boolean
     */
    public function dropIndex($name){
        return $this->doAlterAction('dropIndex', array($name));
    }
    
    /**
     * Add primary key for the operating table.
     * 
     * @param string $columns The name of column to set as primary key
     * @return Management|boolean
     */
    public function addPrimaryKey($columns){
        return $this->doAlterAction('addPrimaryKey', array($columns));
    }
    
    /**
     * Drop primary key for the operating table.
     * 
     * @return Management|boolean
     */
    public function dropPrimaryKey(){
        return $this->doAlterAction('dropPrimaryKey');
    }
    
    /**
     * Add unique column for the operating table.
     * 
     * @param string $columns The name of column to set as unique.
     * @return Management|boolean
     */
    public function addUnique( $columns ){
        return $this->doAlterAction('addUnique', array($columns));
    }
    
    /**
     * Do alter table action by given action name and parms.
     * 
     * @param string $action The action name for alter tabel.
     * @param array $parms The parms to that action
     * @return Management|boolean
     */
    protected function doAlterAction( $action, $parms=array() ) {
        $builder = SQLBuilder::build()->alterTable()->name($this->name);
        $builder = call_user_func_array(array($builder, $action), $parms);
        return self::executeSQLQueryWithOutResult($builder->toString()) ? $this : false;
    }
    
    protected function query( $sql ) {
        $result = X::system()->getServiceManager()->get(XDatabaseService::SERVICE_NAME)->getDb()->query($sql);
        if ( false === $result ) {
            throw new Exception(sprintf('Failed to execute query: %s', $sql));
        }
        return $result;
    }
    
    public function getInformation() {
        $sql = SQLBuilder::build()->describe()->table($this->name)->toString();
        $result = $this->query($sql);
        return $result;
    }
}