<?php
/**
 * Namespace defination
 */
namespace X\Service\XDatabase;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Exception;
use X\Service\XDatabase\Core\Database;

/**
 * XDb Service
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class XDatabaseService extends \X\Core\Service\XService {
    /**
     * This value holds the service instace.
     *
     * @var XService
     */
    protected static $service = null;
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::afterStart()
     */
    protected function afterStart() {
        $this->loadDatabasesFromConfigFile();
    }
    
    /**
     * The name of service
     * 
     * @var string
     */
    const SERVICE_NAME = 'XDatabase';
    
    /**
     * This value contains all the database object that manager is using.
     * 
     * @var Database[]
     */
    protected $databases = array();
    
    /**
     * Load database from configurations.
     * 
     * @return void
     */
    protected function loadDatabasesFromConfigFile() {
        foreach ( $this->configuration['databases'] as $name => $config ) {
            $this->addDb($name, $config);
        }
    }
    
    /**
     * Add db to manager with configurations. If the name already exists,
     * then an error will be throwed.
     * 
     * @param string $name The name of db for db in manager.
     * @param array  $config The configurations for that db.
     * @return void
     */
    public function addDb( $name, $config ) {
        if ( isset($this->databases[$name]) ) {
            throw new Exception(sprintf('Databasb "%s" already exists in db manager.', $name));
        }
        
        $this->databases[$name] = new Database($config);
    }
    
    /**
     * Get db from manager by given name, if the db does not exists,
     * an error will be throwed.
     * 
     * @param string $name The name of db you need.
     * @return Database
     */
    public function getDb( $name=null ) {
        if ( is_null($name) ) {
            $name = $this->current;
        }
        
        $this->checkDbByName($name);
        return $this->databases[$name];
    }
    
    /**
     * Check whether the db exists by given name. 
     * Return true if exists or false if not.
     * 
     * @param string $name The name of db to check
     * @return boolean
     */
    public function hasDb( $name=self::DEFAULT_DB_NAME ) {
        return isset($this->databases[$name]);
    }
    
    /**
     * Default using name of db.
     * 
     * @var string
     */
    const DEFAULT_DB_NAME = 'default';
    
    /**
     * The name of using db name, As default, the db which named 
     * 'default' will be setted as using.
     * 
     * @var string The name of current db.
     */
    protected $current = self::DEFAULT_DB_NAME;
    
    /**
     * Switch current db by given name.
     * If database does not exists, an exception will be throwed.
     * 
     * @param string $name The name of db that would be switch to.
     * @return void
     */
    public function switchTo( $name ) {
        $this->checkDbByName($name);
        $this->current = $name;
    }
    
    /**
     * Get the name of using db.
     * 
     * @return string
     */
    public function getCurrentDbName() {
        return $this->current;
    }
    
    /**
     * Remove database from db manager, if you are removing using
     * db, then the default db will be used as current database.
     * If database does not exists, an exception will be throwed.
     * 
     * @param string $name -- The name of db in manager will be deleted
     * @return null
     */
    public function removeDb( $name ) {
        $this->checkDbByName($name);
        unset($this->databases[$name]);
        if ( $name === $this->current ) {
            $this->current = self::DEFAULT_DB_NAME;
        }
    }
    
    /**
     * Check whether the db exists by given name.
     * 
     * @param string $name -- The name of db in manager will be checked.
     * @throws \X\Database\Exception -- When given name does not exists.
     */
    protected function checkDbByName( $name ) {
        if ( !isset($this->databases[$name]) ) {
            throw new Exception(sprintf('Can not find "%s" in DB manager.', $name));
        }
    }
}

return __NAMESPACE__;