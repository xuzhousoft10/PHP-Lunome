<?php
/**
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version 0.0.0
 * @since   0.0.0
 * @license GLPL V3 <https://www.gnu.org/licenses/lgpl.html>
 */
namespace X\Core;

use X\Core\Util\XUtil;
/**
 * The X class use to handle the hold framword stuff.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class X {
    /**
     * This value contains the instant of X class, and this 
     * would be the only instant of C class. It would set by
     * start() method.
     * 
     * @var X
     */
    protected static $system = null;
    
    /**
     * Start the X framework
     * 
     * @param string $basePath  The base path where the root 
     *                          folder of this project is.
     * @return X
     */
    public static function start( $basePath ){
        if ( is_null(self::$system) ) {
            self::$system = new X($basePath);
        }
        return self::$system;
    }
    
    /**
     * Initiate the X framework
     *
     * @param string $root The base path where the base is.
     *
     * @return void
     */
    protected function __construct( $root ) {
        $this->root = $root;
        chdir($this->root);
        $this->loadConfiguration();
        $this->initInterface();
        
        register_shutdown_function(array($this, 'shutdown'));
        spl_autoload_register(array($this, 'autoloader'));
        
        $this->serviceManager = \X\Core\Service\ServiceManagement::getManager();
        $this->moduleManager = \X\Core\Module\ModuleManagement::getManager();
    }
    
    /**
     * The base path of the framework, it shuold be the place
     * where put your index.php file.
     *
     * @var string
     */
    protected $root = null;
    
    /**
     * Get the base path of framework
     *
     * @return string
     */
    public function getRoot() {
        return $this->root;
    }
    
    /**
     * Get the path of current module or subpath of it if $path is not empty.
     * @param string $path The subpath of the module.
     * @return string
     */
    public function getPath( $path='' ) {
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $path = empty($path) ? $this->getRoot() : $this->getRoot().DIRECTORY_SEPARATOR.$path;
        return $path;
    }
    
    /**
     * This value holds the configurations for X.
     * The value comes form main.php in config directory.
     *
     * @var array
     */
    protected $configuration = array();

    /**
     * Load configuration values into system from main.php
     * configuration file.
     *
     * @return void
     */
    protected function loadConfiguration() {
        $path = $this->getCoreConfigFilePath('main');
        $this->configuration = require $path;
    }
    
    /**
     * Get the file path of core configuration file path.
     * 
     * @param string $name The name of configuration file.
     * @return string
     */
    public function getCoreConfigFilePath( $name ) {
        return implode(DIRECTORY_SEPARATOR, array($this->root, 'Config', $name.'.php'));
    }
    
    public function setConfiguration( $name, $value ) {
        $items = explode('.', $name);
        $item = &$this->configuration;
    
        while ( 0 < count($items) ) {
            $item = &$item[array_shift($items)];
        }
        $item = $value;
    }
    
    public function saveConfiguration() {
        $path = $this->getCoreConfigFilePath('main');
        $data = $this->configuration;
        XUtil::storeArrayToPHPFile($path, $data);
    }
    
    /**
     * Stop the application.
     * 
     * @return void
     */
    public function stop() {
        exit();
    }
    
    /**
     * The shutdown hadler
     *
     * @return void
     */
     public function shutdown() {
         $this->serviceManager->stop();
     }
     
     /**
       * Autoload class by givn name.
       * The class file path should be same as the path of namespace.
       * All the namespaces of class should be start with X.
       *
       * @param string $class The required class name.
       * @return void
       */
     public function autoloader( $class ) {
         $path = explode('\\', $class);
         $path[0] = $this->root;
         $path[count($path)-1] .= '.php';
         $path = implode(DIRECTORY_SEPARATOR, $path);
         if ( is_file($path) ) {
             require $path;
         }
     }
     
     /**
      * This value contains the manager of service managements
      *
      * @var \X\Core\Service\ServiceManagement
      */
     protected $serviceManager = null;
     
     /**
      * Get service manager.
      *
      * @return \X\Core\Service\ServiceManagement
      */
     public function getServiceManager() {
         return $this->serviceManager;
     }
     
     /**
      * This value holds the manager of modules.
      *
      * @var \X\Core\Module\ModuleManagement
      */
     protected $moduleManager = null;
        
     
     /**
      * Get module manager
      *
      * @return \X\Core\Module\ModuleManagement
      */
     public function getModuleManager() {
         return $this->moduleManager;
     }
     
     /**
      * Run this application
      *
      * @return void
      */
     public function run() {
         $this->getServiceManager()->start();
         $this->getModuleManager()->start();
         $this->loadParameters();
         $this->getModuleManager()->run();
     }
     
    /**
     * Get the instace of X class
     * 
     * @throws Exception Throw Exception if X has not been started.
     * @return \X\Core\X
     */
    public static function system() {
        if ( is_null(self::$system) ) {
            throw new Exception('X has not been started.');
        }
        return self::$system;
    }
    
    /**
     * This value holds all the shortcut function calls.
     *
     * @var array
     */
    protected $shortCutFunctions = array(
            // 'name'   => $handler,
    );

    /**
     * Register a new shotcut function into framework.
     *
     * @param name $name The shutcut function's name.
     * @param callback $handler The handler of the shutcut functino.
     *
     * @return void
     */
    public function registerShortcutFunction( $name, $handler ) {
        $this->shortCutFunctions[$name] = $handler;
    }
    
    /**
     * The magic call function to implment the shotcut call.
     *
     * @param string $name The name of shortcut function to call
     * @param array $parameters The parameters to handler.
     * @return mixed
     */
    public function __call( $name, $parameters ) {
        if ( isset($this->shortCutFunctions[$name]) ) {
            return call_user_func_array($this->shortCutFunctions[$name], $parameters);
        }
        throw new Exception(sprintf('Can not find shoutcut method "%s".', $name));
    }
    
    /**
     * The parameters to X.
     *
     * @var array
     */
    protected $parameters = array();
    
    /**
     * Setup default parameters
     *
     * @return void
     */
    protected function loadParameters() {
        if ( $this->isCLI() ) {
            $this->parameters = $this->initParametersInCLIMode();
        } else {
            $this->parameters = array_merge($_GET, $_POST, $_REQUEST);
        }
    }
    
    /**
     * Initiate the parameters in cli mode.
     * 
     * @return array
     */
    protected function initParametersInCLIMode() {
        global $argv;
        $parameters = array();
        foreach ( $argv as $index => $parm ) {
            if ( '--' !== substr($parm, 0, 2) ) {
                continue;
            }
            
            list( $name, $value ) = explode('=', $parm);
            $name = substr($name, 2);
            $parameters[$name] = $value;
        }
        return $parameters;
    }
    
    /**
     * Get the parameters for the system.
     * 
     * @return multitype:
     */
    public function getParameters() {
        return $this->parameters;
    }
    
    /**
     * Set the parameters to the system.
     * 
     * @param array $parameters The new parameters
     */
    public function setParameters( $parameters ) {
        $this->parameters = $parameters;
    }
    
    /**
     * A lowercase string that describes the type of interface 
     * (the Server API, SAPI) that PHP is using.
     * 
     * @var string
     */
    protected $interfaceType = null;
    
    /**
     * Initiate the interface that PHP is using.
     * 
     * @return void
     */
    protected function initInterface() {
        $this->interfaceType = php_sapi_name();
    }
    
    /**
     * 
     * @var string
     */
    const PHP_INTERFACE_TYPE_CLI = 'cli';
    
    /**
     * Check if using CLI interface.
     * 
     * @return boolean
     */
    public function isCLI() {
        return self::PHP_INTERFACE_TYPE_CLI == $this->interfaceType;
    }
}