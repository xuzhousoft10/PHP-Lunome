<?php
/**
 * Namespace defination
 */
namespace X\Core\Service;

use X\Core\Util\XUtil;
/**
 * The Service basic service class.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @since   Version 0.0.0
 * @version 0.0.0
 */
abstract class XService extends \X\Core\Basic {
    /**
     * Get the service name from given name.
     * 
     * @param string $className
     * @return string
     */
    private static function getServiceNameFromClassName( $className ) {
        $className = explode('\\', $className);
        return str_replace('Service', '', array_pop($className));
    }
    
    /**
     * Get service name by a non-static way.
     * 
     * @return string
     */
    public function getName() {
        return self::getServiceNameFromClassName(get_class($this));
    }
    
    /**
     * The static way to get service name.
     * 
     * @return string
     */
    public static function getServiceName() {
        return self::getServiceNameFromClassName(get_called_class());
    }
    
    /**
     * This value holds the service instace.
     * 
     * @var XService
     */
    protected static $service;
    
    /**
     * Get service instance
     * 
     * @return XService
     */
    static public function getService() {
        $className = get_called_class();
        if ( is_null(static::$service) ) {
            static::$service = new $className();
        }
        
        return static::$service;
    }
    
    /**
     * Do not allow user instance this class at somewhere else.
     * 
     * @return void
     */
    protected function __construct() {}
    
    /**
     * This value contains all configuration values.
     * 
     * @var array
     */
    protected $configuration = array();
    
    /**
     * Load configurations from config directory, before loaded
     * the config, it would clean the old configurations. 
     * If the configuration file does not exists, then nothing would 
     * be loaded. 
     * The configuration should be name as 'main.php', unless you 
     * overite the method getConfigFileName().
     * 
     * @return void
     */
    protected function loadConfiguration() {
        $this->configuration = array();
        $configurationPath = $this->getConfigFilePath();
        if ( !file_exists($configurationPath) ) {
            return;
        } else {
            $this->configuration = require $configurationPath;
        }
    }
    
    public function setConfiguration( $name, $value ) {
        $items = explode('.', $name);
        $item = &$this->configuration;
        
        while ( 0 < count($items) ) {
            $item = &$item[array_shift($items)];
        }
        $item = $value;
    }
    
    /**
     * Update the configuration file of this service.
     * If the configuration file does not exists, then the 
     * new configuration would be created, or the old one 
     * would be overrited.
     * If the configuration is empty, then the configuration 
     * file would be deleted.
     * Also, this method would crate the configuration folder
     * automatically.
     * 
     * @return void
     */
    public function saveConfiguration() {
        $configurationPath = $this->getConfigFilePath();
        
        if ( file_exists($configurationPath)) {
            unlink($configurationPath);
        }
        
        if ( empty($this->configuration) ) {
            return;
        }
        
        $configurationDirectory = dirname($configurationPath);
        if ( !file_exists($configurationDirectory) ) {
            mkdir($configurationDirectory);
        }
        
        XUtil::storeArrayToPHPFile($configurationPath, $this->configuration);
    }
    
    /**
     * Get the path of config file.
     * 
     * @return string
     */
    protected function getConfigFilePath() {
        $servicePath = $this->getServicePath();
        $configFileName = $this->getConfigFileName();
        $configurationPath = sprintf('%s/Config/%s', $servicePath, $configFileName);
        return $configurationPath;
    }
    
    /**
     * Get the path where the service stored.
     * 
     * @return string
     */
    public function getServicePath( $path='' ) {
        $service = new \ReflectionClass(get_class($this));
        $servicePath = dirname($service->getFileName());
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $path = $servicePath.DIRECTORY_SEPARATOR.$path;
        return $path;
    }
    
    /**
     * Get the file name of configuration file name.
     * 
     * @return string
     */
    protected function getConfigFileName() {
        return 'Main.php';
    }
    
    /**
     * Start the service.
     * This method would be called by service management class.
     * 
     * @return void
     */
    public function start(){
        $this->beforeStart();
        $this->loadConfiguration();
        $this->afterStart();
    }
    
    /**
     * The method that would be executed before the service started.
     * 
     * @return void
     */
    protected function beforeStart(){}
    
    /**
     * The method that would be executed after the service started.
     * 
     * @return void
     */
    protected function afterStart(){}
    
    /**
     * Stop this service.
     * This method would be called when the script exit.
     * 
     *  @return void
     */
    public function stop(){
        $this->beforeStop();
        $this->afterStop();
    }
    
    /**
     * The method that would be executed before the service stopped.
     *
     * @return void
     */
    protected function beforeStop(){}
    
    /**
     * The method that would be executed after the service stopped.
     *
     * @return void
     */
    protected function afterStop(){}
    
    /**
     * 
     * @var unknown
     */
    protected $settingController = null;
    
    /**
     * Load setting controller for current service.
     * If the custom controller can not be found, then, the default
     * controller would be used as controller for current service.
     * 
     * @return void
     */
    public function getSettingController() {
        if ( !is_null($this->settingController) ) {
            return $this->settingController;
        }
        
        $controller = get_class($this);
        $controller = explode('\\', $controller);
        array_pop($controller);
        $controller = implode('\\', $controller).'\\Controller\\SettingController';
        
        if ( class_exists($controller) ) {
            $this->settingController = new $controller($this);
        } else {
            $this->settingController = new SettingController($this);
        }
        
        return $this->settingController;
    }
}