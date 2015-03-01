<?php
/**
 * table.php
 */
namespace X\Service\XDatabase\Core\Table;

/**
 * Use statements
 */
use X\Core\X;
use X\Service\XDatabase\Core\Exception;
use X\Service\XDatabase\Service as XDatabaseService;
use X\Service\XDatabase\Core\SQL\Builder as SQLBuilder;

/**
 * Table
 *
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Manager {
    /**
     * Get table names in the database.
     */
    public static function getTables() {
        return self::getDatabase()->getTables();
    }
    
    /**
     * Create a new table.
     * 
     * @param string $name The name of table to create
     * @param array $columns The column definitions.
     * @return Management
     */
    public static function create( $name, $columns, $primaryKey=null ) {
        $sql = SQLBuilder::build()->createTable()
            ->name($name)
            ->columns($columns)
            ->primaryKey($primaryKey)
            ->toString();
        
        self::executeSQLQueryWithOutResult($sql);
        return new Manager($name);
    }
    
    /**
     * Get a new table object by name.
     * 
     * @param string $name The name of table to open for operation.
     * @return Manager
     */
    public static function open( $name ) {
        $manager = new Manager($name);
        $manager->getInformation();
        return $manager;
    }
    
    /**
     * Get the information about table.
     * @return unknown
     */
    public function getInformation() {
        $sql = SQLBuilder::build()->describe()->name($this->name)->toString();
        $result = $this->query($sql);
        return $result;
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
        self::executeSQLQueryWithOutResult($sql);
        return $this;
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
        self::executeSQLQueryWithOutResult($query);
        return $this;
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
        self::executeSQLQueryWithOutResult($query);
        return $this;
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
        self::executeSQLQueryWithOutResult($query);
        $this->name = $name;
        return $this;
    }
    
    /**
     * Add new column to the operating table.
     * 
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
     * Change column from the operating table.
     * 
     * @param string $name The name of column to change
     * @param string $definition The new definition for column
     * @return Management|boolean
     */
    public function changeColumn($name, $definition, $newName=null){
        $newName = (null===$newName) ? $name : $newName;
        return $this->doAlterAction('changeColumn', array($name, $newName, $definition));
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
     * @return Management
     */
    private function doAlterAction( $action, $parms=array() ) {
        $builder = SQLBuilder::build()->alterTable()->name($this->name);
        $builder = call_user_func_array(array($builder, $action), $parms);
        self::executeSQLQueryWithOutResult($builder->toString());
        return $this;
    }
    
    /**
     * The name of the target table.
     *
     * @var string
     */
    private $name = null;
    
    /**
     * Initiate the object by given table name
     *
     * @param string $name The name of table to operate.
     */
    private function __construct( $name ) {
        $this->name = $name;
    }
    
    /**
     * Execute a query an return the result.
     * @param unknown $sql
     * @throws Exception
     * @return unknown
     */
    private function query( $sql ) {
        $result = self::getDatabase()->query($sql);
        return $result;
    }
    
    /**
     * Get the xdatabse service.
     * @return \X\Service\XDatabase\Core\Database\Database
     */
    private static function getDatabase() {
        $service = X::system()->getServiceManager()->get(XDatabaseService::getServiceName());
        return $service->getDatabaseManager()->get();
    }
    
    /**
     * executeSQLQueryWithOutResult
     *
     * @param string $query The query to execute
     * @return boolean
     */
    private static function executeSQLQueryWithOutResult( $query ) {
        self::getDatabase()->exec($query);
    }
}