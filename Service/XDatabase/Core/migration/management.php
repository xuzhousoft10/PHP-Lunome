<?php
/**
 * manager.php
 */
namespace X\Database\Migration;
/**
 * Management
 * 
 * @author  Michael Luthor <michael.the.ranidae@gamil.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Management extends \X\Database\Base {
    /**
     * The order name of how to sort the migration scripts.
     * 
     * @var integer
     */
    const SCANDIR_SORT_ASCENDING = 0;
    
    /**
     * The order name of how to sort the migration scripts.
     * 
     * @var integer
     */
    const SCANDIR_SORT_DESCENDING = 1;
    
    /**
     * This value contains the management class object.
     * 
     * @var Management
     */
    protected static $manager = null;
    
   /**
    * Get manager from management.
    * 
    * @return Management
    */
    public static function manager() {
        return self::$manager = ( is_null(self::$manager) ) ? new Management() : self::$manager;
    }
    
    /**
     * Initiate the management object. It can not instantiate from 
     * others, so that we can keep there is only one manager in the
     * system.
     */
    protected function __construct() {}
    
    /**
     * This value contains all the migration path.
     * 
     * @see Management::setMigrationPath() How to set migration path
     * @var array
     */
    protected $migrationPaths = array();
    
    /**
     * Add migration path to migrate manager.
     * 
     * @param string $path The path of migration scripts.
     * @return Management
     */
    public function setMigrationPath ( $path ) {
        if ( false !== array_search($path, $this->migrationPaths)) {
            return $this;
        }
        $this->migrationPaths[] = $path;
        return $this;
    }
    
    /**
     * Get all migration scripts from setted paths. if successed,
     * an array contains all migration scripts would be returned.
     * 
     * @param integer $order The order how to sort the migration scripts.
     * @return array
     */
    protected function getMigrationFileList( $order=self::SCANDIR_SORT_ASCENDING ) {
        $list = array();
        foreach ( $this->migrationPaths as $path ) {
            if ( DIRECTORY_SEPARATOR !== $path[strlen($path)-1] ) {
                $path .= DIRECTORY_SEPARATOR;
            }
            
            $migrationsFiles = scandir($path, $order);
            foreach ( $migrationsFiles as $index => &$migrationsFile ) {
                if ( '.' === $migrationsFile[0] ) {
                    unset($migrationsFiles[$index]);
                }
                else {
                    $migrationsFile = $path.$migrationsFile;
                }
            }
            $list = array_merge($list, $migrationsFiles);
        }
        return $list;
    }
    
    /**
     * Get the migration history. Return an array contains each 
     * script's information.
     * 
     * @return array
     */
    protected function getMigrationHistory() {
        $migrateHistoryList = file(dirname(__FILE__).'/migration.history');
        $histories = array();
        foreach ( $migrateHistoryList as $record ) {
            $info = explode("\t", $record);
            $histories[$info[0]] = str_replace("\n", '', $info[1]);
        }
        return $histories;
    }
    
    /**
     * Update the migration history by given history information.
     * 
     * @param array $history The history information to save.
     * @return void
     */
    protected function updateMigrationHistory( $history ) {
        foreach ( $history as $path => &$record ) {
            $record = sprintf("%s\t%s", $path, $record);
        }
        $history = implode("\n", $history);
        file_put_contents(dirname(__FILE__).'/migration.history', $history);
    }
    
    /**
     * Update the database to the last version by migration scripts.
     * 
     * @see Management::setMigrationPath() How to set migration path
     * @return void
     */
    public function up() {
        $migrations = $this->getMigrationFileList();
        $migrationHistory = $this->getMigrationHistory();
        foreach ( $migrations as $migration ) {
            if ( isset($migrationHistory[$migration]) ) {
                continue;
            }
            
            $migrationInfo = explode('.', $migration);
            array_pop($migrationInfo);
            $class = array_pop($migrationInfo);
            
            if ( !class_exists($class, false) ) {
                require $migration;
            }
            $migrate = new $class();
            $migrate->up();
            $migrationHistory[$migration] = 'OK';
        }
        $this->updateMigrationHistory($migrationHistory);
    }
    
    /**
     * Degrade the database to the first version by migration scripts.
     * 
     * @see Management::setMigrationPath() How to set migration path
     * @return void
     */
    public function down() {
        $migrations = $this->getMigrationFileList(self::SCANDIR_SORT_DESCENDING);
        $migrationHistory = $this->getMigrationHistory();
        foreach ( $migrations as $migration ) {
            if ( !isset($migrationHistory[$migration]) ) {
                continue;
            }
            
            $migrationInfo = explode('.', $migration);
            array_pop($migrationInfo);
            $class = array_pop($migrationInfo);
            
            if ( !class_exists($class, false) ) {
                require $migration;
            }
            $migrate = new $class();
            $migrate->down();
            unset($migrationHistory[$migration]);
        }
        $this->updateMigrationHistory($migrationHistory);
    }
}