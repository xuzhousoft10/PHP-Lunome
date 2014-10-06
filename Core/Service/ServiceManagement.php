<?php
/**
 * This file defines the service management class.
 */
namespace X\Core\Service;

/**
 * Use statements
 */
use X\Core\X;
use X\Core\Util\XUtil;

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
         *          'class'   =>'namespace\\class'))
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
        $serviceClass = $configuration['class'];
        if ( !class_exists($configuration['class']) ) {
            throw new Exception(sprintf('Can not find service "%s"', $name));
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
    /**
     * Create a new service by given name.
     * 
     * @param string $name The name service.
     * @param string $module The module that the service belongs to, if null then it belongs to system.
     */
    public function create ( $name, $module=null ) {
        if ( $this->has($name) ) {
            throw new Exception(sprintf('Service "%s" already exists.', $name));
        }
        if ( null === $module && !X::system()->getModuleManager()->has($module) ) {
            throw new Exception(sprintf('Module "%s" does not exists.', $name));
        }
        
        /* Generate the namespace of servie */
        $namespace = "X\\Service\\$name";
        if ( null !== $module ) {
            $namespace = "X\\Module\\$module\\Service\\$name";
        }
        
        /* Generate the service file content. */
        $content    = array();
        $content[]  = "<?php";
        $content[]  = "/**\n * This file implements the service Movie\n */";
        $content[]  = "namespace $namespace;";
        $content[]  = "";
        $content[]  = "/**\n * The service class\n */";
        $content[]  = "class Service extends \\X\\Core\\Service\\XService {";
        $content[]  = "}";
        $content = implode("\n", $content);
        
        /* Generate the path of service */
        $path = X::system()->getPath("Service/$name");
        if ( null !== $module ) {
            $path = X::system()->getPath("Module/$module/Service");
            if ( !is_dir($path) ) {
                mkdir($path);
            }
            $path = X::system()->getPath("Module/$module/Service/$name");
        }
        if ( !is_dir($path) ) {
            mkdir($path);
        }
        $path = $path.DIRECTORY_SEPARATOR.'Service.php';
        
        /* Save the service */
        file_put_contents($path, $content);
        
        /* Update the configuration */
        $this->configuration['services'][$name]['enable'] = false;
        $this->configuration['services'][$name]['class'] = "$namespace\\Service";
        $this->saveConfigurations();
    }
    
    /**
     * Check the service exists by given name.
     * 
     * @param string $name The name of service to check.
     */
    public function has( $name ) {
        return array_key_exists($name, $this->services);
    }
    
    public function getList() {
        return array_keys($this->services);
    }
    
    /**
     * Save the configuration file into fs.
     * @return void
     */
    private function saveConfigurations() {
        $path = X::system()->getCoreConfigFilePath('services');
        XUtil::storeArrayToPHPFile($path, $this->configuration['services']);
    }
}