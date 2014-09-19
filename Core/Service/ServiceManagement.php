<?php
/**
 * This file defines the service management class.
 */
namespace X\Core\Service;

/**
 * Use statements
 */
use X\Core\X;

/**
 * The service management class.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @since   Version 0.0.0
 */
class ServiceManagement extends \X\Core\Basic {
    /**
     * This value holds the manager instance.
     *
     * @var ServiceManagement
     */
    protected static $manager = null;
    
    /**
     * Get the service manager instance
     * 
     * @return \X\Core\Service\ServiceManagement
     */
    public static function getManager() {
        if ( is_null(self::$manager) ) {
            self::$manager = new ServiceManagement();
        }
        
        return self::$manager;
    }
    
    /**
     * Start the management to load the service management config and 
     * all the availabel service.
     * 
     * @return void
     */
    public function start(){
        $this->loadConfiguration();
        $this->loadServices();
    }
    
    /**
     * This value holds the configuration of manager.
     * -- services : Store the service informations. 
     *  The values come from services.php configuration file.
     * 
     * @var array
     */
    protected $configuration = array(
        /**
         * 'services' => array(
         *      'service_name'=> array(
         *          'enable' =>true, 
         *          'path'   =>'service/path'))
         */
    );
    
    /**
     * Load configuration into management.
     *
     * @return void
     */
    protected function loadConfiguration() {
        $configPath = X::system()->getCoreConfigFilePath('services');
        $services = require $configPath;
        $this->configuration['services'] = $services;
    }
    
    /**
     * This value holds all the service instances and running information.
     *
     * @var array
     */
    protected $services = array(
        /**
         * 'name' => array(
         *      'enable'    => true, 
         *      'service'   => $service)
         */
    );
    
    /**
     * Load all enabled services from configuration.
     *
     * @return void
     */
    protected function loadServices() {
        $services = $this->configuration['services'];
        foreach ( $services as $name => $configuration ) {
            if ( $configuration['enable'] ) {
                $this->loadService($name, $configuration);
            } else {
                $this->services[$name]['enable'] = false;
                $this->services[$name]['service'] = null;
            }
        }
    }
    
    /**
     * Load service into management.
     *
     * @param string $name The name of service to load.
     * @param array $configuration The configuration for the service.
     */
    public function loadService($name, $configuration) {
        $basePath = X::system()->getRoot();
        $servicePath = $basePath.DIRECTORY_SEPARATOR.$configuration['path'];
        if ( !is_file($servicePath) ) {
            throw new Exception(sprintf('Can not find service "%s" in "%s".', $name, $servicePath));
        }
        
        $namespace = require $servicePath;
        $namespace = is_string($namespace) ? $namespace : '';
        $serviceClass = $namespace.'\\'.$name.'Service';
        if ( !class_exists($serviceClass, false) ) {
            throw new Exception(sprintf('Can not find service "%s".', $name));
        }
        if ( !( is_subclass_of($serviceClass, '\\X\\Core\\Service\\XService') ) ) {
            throw new Exception(sprintf('"%s" is not a available service.'));
        }
        $service = $serviceClass::getService();
        
        $service->start();
        
        $this->services[$name]['enable'] = true;
        $this->services[$name]['service'] = $service;
    }
    
    /**
     * Get service from service management.
     *
     * @param string $serviceName The name of service to get.
     *
     * @return \X\Core\Service\XService
     */
    public function get( $serviceName ) {
        if ( !isset($this->services[$serviceName]) ) {
            throw new Exception(sprintf('Unknown service "%s".', $serviceName));
        }
        return $this->services[$serviceName]['service'];
    }
    
    /**
     * Stop the management and stop all the running services.
     *
     * @return void
     */
    public function stop() {
        foreach ( $this->services as $name => $service ) {
            if( !$service['enable'] ) {  continue; }
            $service['service']->stop();
        }
    }
    
    /************ Management ***********************************/
    public function getList() {
        return array_keys($this->services);
    }
}