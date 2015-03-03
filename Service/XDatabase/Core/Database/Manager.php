<?php
namespace X\Service\XDatabase\Core\Database;

/**
 * 
 */
use X\Core\X;
use X\Core\Util\Manager as CoreManager;
use X\Service\XDatabase\Service;
use X\Service\XDatabase\Core\Util\Exception;

/**
 * 
 */
class Manager extends CoreManager {
    /**
     * @var array
     */
    private $databases = array();
    
    /**
     * @var  Service
     */
    private $service = null;
    
    /**
     * @var string
     */
    const DEFAULT_DATADASE = 'default';
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\Manager::start()
     */
    public function start() {
        parent::start();
        $this->service = X::system()->getServiceManager()->get(Service::getServiceName());
        
        $definedDatabases = $this->service->getConfiguration()->get('databases', array());
        foreach ( $definedDatabases as $name => $config ) {
            $this->load($name, $config);
        }
        $this->currentDatabaseName = self::DEFAULT_DATADASE;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\Manager::stop()
     */
    public function stop() {
        $this->service = null;
        $this->databases = array();
        parent::stop();
    }
    
    /**
     * @param unknown $name
     * @param unknown $config
     */
    public function load( $name, $config ) {
        $this->dbNotExistsRequired($name);
        $this->databases[$name] = new Database($config);
    }
    
    /**
     * @param string $name
     * @param array $config
     */
    public function register( $name, $config ) {
        $this->load($name, $config);
        
        $configuration = $this->service->getConfiguration();
        if ( !isset($configuration['databases']) ) {
            $configuration['databases'] = array();
        }
        $configuration['databases'][$name]=$config;
        $configuration->save();
    }
    
    /**
     * @param string $name
     */
    public function unregister( $name ) {
        $this->dbExistsRequired($name);
        unset($this->databases[$name]);
        if ( $this->currentDatabaseName === $name ) {
            $this->currentDatabaseName = null;
        }
        
        $configuration = $this->service->getConfiguration();
        unset($configuration['databases'][$name]);
        $configuration->save();
    }
    
    /**
     * @param string $name
     * @return boolean
     */
    public function has( $name ) {
        return isset($this->databases[$name]);
    }
    
    /**
     * @var string
     */
    private $currentDatabaseName = null;
    
    /**
     * @param string $name
     * @return \X\Service\XDatabase\Core\Database\Database
     */
    public function get( $name=null ) {
        if ( null === $name ) {
            $name = $this->currentDatabaseName;
        }
        $this->dbExistsRequired($name);
        return $this->databases[$name];
    }
    
    /**
     * @return string
     */
    public function getCurrentName() {
        return $this->currentDatabaseName;
    }
    
    /**
     * @param string $name
     */
    public function switchTo( $name ) {
        $this->dbExistsRequired($name);
        $this->currentDatabaseName = $name;
    }
    
    /**
     * @param string $name
     * @throws Exception
     */
    private function dbNotExistsRequired($name) {
        if ( $this->has($name) ) {
            throw new Exception('Database "'.$name.'" already exists.');
        }
    }
    
    /**
     * @param string $name
     * @throws Exception
     */
    private function dbExistsRequired( $name ) {
        if ( !$this->has($name) ) {
            throw new Exception('Database "'.$name.'" does not exists.');
        }
    }
}