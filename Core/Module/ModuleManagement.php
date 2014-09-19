<?php
/**
 * This file defines the module management class.
 */
namespace X\Core\Module;

/**
 * Use statements
 */
use X\Core\X;

/**
 * The module management class.
 * This class use to manage all the modules. 
 * You can install, import or other stuff to manage the module 
 * by this class. Do not management the modules by manually, 
 * unless you know what you are doing.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 * 
 */
class ModuleManagement extends \X\Core\Basic {
    /**
     * This value contains the manager of ModuleManagement
     * 
     * @var ModuleManagement
     */
    protected static $manager = null;
    
    /**
     * Get the manager of management.
     * 
     * @return ModuleManagement
     */
    public static function getManager() {
        if ( is_null(self::$manager) ) {
            self::$manager = new ModuleManagement();
        }
        return self::$manager;
    }
    
    /**
     * Start module management to load all the availabel modules
     * and configuration for module management.
     * If the module is not a available module, then it would pass
     * to load it.
     *
     * @return void
     */
    public function start() {
        $this->loadConfig();
        $this->loadModules();
    }
    
    /**
     * This value contains the configuration of management.
     *
     * @var array
     */
    protected $configuration = array();
    
    /**
     * Load management configurations
     *
     * @return void
     */
    protected function loadConfig() {
        $path = X::system()->getCoreConfigFilePath('modules');
        $this->configuration['modules'] = require $path;
    }
    
    /**
     * This value contains the loaded modules.
     * 
     * @var \X\Core\Module\XModule[]
     */
    protected $modules = array();

    /**
     * Load all available modules.
     *
     * @return void
     */
    protected function loadModules() {
        $modulePath = $this->getModuleStorePath();
        $moduleNames = scandir($modulePath, SCANDIR_SORT_NONE);
        array_map(array($this, 'doInitLoadModule'), $moduleNames);
    }
    
    /**
     * Get the path where the modules stored at.
     *
     * @return string
     */
    protected function getModuleStorePath() {
        $basePath = X::system()->getRoot();
        $modulePath = $basePath.DIRECTORY_SEPARATOR.'Module';
        return $modulePath;
    }

    /**
     * Check the module, if it's not available module then ignore it, but
     * save the module information to management.
     *
     * @param string $name The name of module to load.
     *
     * @return boolean
     */
    protected function doInitLoadModule( $name ) {
        if ( '.' == $name[0] ) {
            return false;
        }

        return $this->loadModule($name);
    }
    
    /**
     * Load sigle module by name into management.
     *
     * @param string $name The name of module to load.
     *
     * @throws X\Core\Module\Exception When the module class can not be found.
     * @throws X\Core\Module\Exception When the module class is not extens from XModule
     *
     * @return boolean Whether the module has been loaded or not.
     */
    protected function loadModule($name) {
        if ( !isset($this->configuration['modules'][$name]) || !$this->configuration['modules'][$name]['enable'] ) {
            $this->modules[$name]['available'] = false;
            $this->modules[$name]['is_loaded'] = false;
            $this->modules[$name]['module'] = null;
            return false;
        }
        
        $moduleClass = sprintf('X\\Module\\%s\\Module', $name);
        if ( !class_exists($moduleClass) ) {
            throw new Exception(sprintf('Module handler "%s" can not be found.', $moduleClass));
        }
        $module = new $moduleClass($name);
        if ( !($module instanceof XModule ) ) {
            throw new Exception(sprintf('Module "%s" is not a available module.', $name));
        }
        
        $this->modules[$name]['available'] = true;
        $this->modules[$name]['is_loaded'] = true;
        $this->modules[$name]['module'] = $module;
        return true;
    }
    
    /**
     * Execute the module by get module name from system parameters.
     * If there is not availabel module name, then the default module
     * would be executed.
     *
     * @return void
     */
    public function run() {
        $parameters = X::system()->getParameters();
        $moduleName = isset($parameters['module']) ? $parameters['module'] : $this->configuration['default'];
        $moduleName = ucfirst($moduleName);
        
        if ( !isset($this->modules[$moduleName]) ) {
            throw new Exception(sprintf('Module "%s" can not be found.', $moduleName));
        }
        if ( !$this->modules[$moduleName]['available'] ) {
            throw new Exception(sprintf('Module "%s" is not avilable.', $moduleName));
        }
        if ( !$this->modules[$moduleName]['is_loaded'] ) {
            throw new Exception(sprintf('Module "%s" is not loaded.', $moduleName));
        }
        
        unset($parameters['module']);
        /* @var $module \X\Core\Module\XModule */
        $module = $this->modules[$moduleName]['module'];
        $module->run($parameters);
    }
    
    /**
     * Get module by name form management.
     *
     * @param string $name The name of module in management.
     *
     * @return \X\Core\Module\XModule
     */
    public function get( $name ) {
        if ( !isset($this->modules[$name]) ) {
            throw new Exception(sprintf('Can not find module "%s".', $name));
        }

        if ( !$this->modules[$name]['is_loaded'] ) {
            $this->loadModule($name);
        }
        return $this->modules[$name]['module'];
    }
    
    /************  Management  *************************************************/
    
    public function getList() {
        return array_keys($this->modules);
    }
    
    /**
     * Check wether the module is enabled. return true is the module is enabled.
     * 
     * @param string $name The name of module to check.
     * @return boolean
     */
    public function isEnable( $name ) {
        return isset($this->configuration['modules'][$name]) && $this->configuration['modules'][$name]['enable'];
    }
    
    /**
     * Enable the module by given name.
     * 
     * @param string $name The name of module to enable.
     * @return void
     */
    public function enable( $name ) {
        if ( !$this->has($name) ) {
            throw new Exception(sprintf('Module "%s" does not exists.', $name));
        }
        
        $this->configuration['modules'][$name]['enable'] = true;
        $this->saveConfigurations();
    }
    
    /**
     * Disable the module by given name.
     * 
     * @param string $name The name of module to disable.
     * @return void
     */
    public function disable( $name ) {
        if ( !$this->has($name) ) {
            throw new Exception(sprintf('Module "%s" does not exists.', $name));
        }
        
        $this->configuration['modules'][$name]['enable'] = false;
        $this->saveConfigurations();
    }
    
    public function has( $moduleName ) {
        return isset($this->modules[$moduleName]);
    }
    
    public function create( $name ) {
        $moduleName = ucfirst($name);
        if ( $this->has($moduleName) ) {
            throw new Exception(sprintf('Module "%s" has already exists.', $name));
        }
        
        $basePath = X::system()->getPath('Module/'.$moduleName);
        mkdir($basePath);
        
        $moduleFile = <<<EOT
<?php
namespace X\\Module\\$moduleName;
class Module extends \\X\\Core\\Module\\XModule {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::run()
     */
    public function run(\$parameters = array()) {
        echo "This is a new module.";
    }
}
EOT;
        $moduleFilePath = X::system()->getPath("Module/$moduleName/Module.php");
        file_put_contents($moduleFilePath, $moduleFile);
        
        $this->configuration['modules'][$moduleName] = array('enable'=>false);
        $this->saveConfigurations();
        $this->loadModule($moduleName);
    }
    
    public function delete( $name ) {
        $name = ucfirst($name);
        $path = X::system()->getPath("Module/$name");
        $this->deleteFolder($path);
        
        unset($this->configuration['modules'][$name]);
        $this->saveConfigurations();
        unset($this->modules[$name]);
    }
    
    private function saveConfigurations() {
        $path = X::system()->getCoreConfigFilePath('modules');
        $content = array();
        $content[] = '<?php';
        $content[] = 'return';
        $content[] = var_export($this->configuration['modules'], true);
        $content[] = ';';
        $content = implode("\n", $content);
        file_put_contents($path, $content);
    }
    
    private function deleteFolder( $path ) {
        if ( !file_exists($path) ) {
            return false;
        }

        $files = array_diff(scandir($path), array('.','..'));
        foreach ($files as $file) {
            $filePath = $path.DIRECTORY_SEPARATOR.$file;
            (is_dir($filePath) && !is_link($path)) ? $this->deleteFolder($filePath) : unlink($filePath);
        }
        return rmdir($path);
    }
    
//     /**
//      * Shutdown the management.
//      * With this method, you can get clean manager afer you call getManager().
//      * 
//      * @return void
//      */
//     public function shutdown() {
//         self::$manager = null;
//     }
    
//     /**
//      * Get the module name list in management.
//      * 
//      * @return string[]
//      */
//     public function getModules() {
//         return array_keys($this->modules);
//     }
    
    
//     /**
//      * Install the module by name
//      * 
//      * @param string $name The name of module to install.
//      * @param string $message The value to store the error message.
//      * 
//      * @return boolean 
//      */
//     public function installModule( $name, &$message = null ){
//         $module = $this->getModule($name);
//         if ( is_null($module) ) {
//             $message=sprintf('"%s" is not a available module.', $name);
//             return false; 
//         }
        
//         if ( !$module->install($message) ) {
//             return false;
//         }
        
//         $ini = Ini::read($this->getModuleConfigFilePath(), true);
//         $ini->addItem('enable', 'yes', $name);
//         if ( !$ini->save() ) {
//             $message = sprintf('"%s" can not be installed', $name);
//             return false;
//         }
        
//         return true;
//     }
    
//     /**
//      * Uninstall module by name.
//      * This would disable the module but not delete it.
//      * 
//      * @param string $name The name of module to uninstall.
//      * 
//      * @return boolean
//      */
//     public function uninstallModule( $name, &$message=null ) {
//         $module = $this->getModule($name);
//         if ( is_null($module) ) {
//             $message = sprintf('"%s" is not an available module.', $name);
//             return false;
//         }
        
//         if ( !$module->uninstall( $message ) ) {
//             return false;
//         }
//         $ini = Ini::read($this->getModuleConfigFilePath(), true);
//         $ini->addItem('enable', 'no', $name);
//         $ini->save();
//         return true;
//     }
    
//     /**
//      * Update module by given name.
//      * This method would execute the update process for a module.
//      * 
//      * @param string $name The name of module to update.
//      * @param string $message The value to store the error message, If there is not error
//      *                          then it would not be changed.
//      * @param array $list The value to store the items of update progress.
//      * 
//      * @return boolean
//      */
//     public function upgradeModule( $name, &$message=null, &$list=array() ) {
//         $module = $this->getModule($name);
//         if ( is_null($module) ) {
//             $message = sprintf('"%s" is not an available module.', $name);
//             return false;
//         }
        
//         if ( !$module->upgrade( $message, $list ) ) {
//             return false;
//         }
        
//         return true;
//     }
    
//     /**
//      * Delete a module by given name.
//      * 
//      * @param string $name The name of module to delete.
//      * 
//      * @return boolean
//      */
//     public function deleteModule( $name ) {
//         $path = $this->getModulePath($name);
//         return $this->deleteFolder($path);
//     }
    
//     /**
//      * Import a new module into system from zip file.
//      * 
//      * @param string $name The name of the target module.
//      * @param string $file The path of zip file.
//      * 
//      * @return boolean
//      */
//     public function importModule( $name, $file ) {
//         $zip = new \ZipArchive();
//         $isImported = false;
//         if ( true === $zip->open($file) ) {
//             $modulePath = $this->getModulePath($name, false);
//             mkdir($modulePath);
//             $zip->extractTo($modulePath);
//             $isImported = true;
//         }
        
//         $zip->close();
//         return $isImported;
//     }
    
//     /**
//      * Set default module by given name.
//      * 
//      * @param string $name The name of module to set as default module.
//      * 
//      * @return boolean
//      */
//     public function setDefaultModule( $name ) {
//         $ini = Ini::read($this->getModuleConfigFilePath(), true);
//         $data = $ini->getData();
//         foreach ( $data as $moduleName => &$config ) {
//             $config['default'] = ( $moduleName == $name ) ? 'yes' : 'no';
//         }
//         $ini->setData($data);
//         return $ini->save();
//     }
    
//     /**
//      * Check whether the module is default module.
//      * 
//      * @param string $name The name of the moduel to check.
//      * 
//      * @return boolean
//      */
//     public function isDefault( $name ) {
//         $isDefault = isset($this->configuration['modules'][$name]['default']);
//         $isDefault = $isDefault && $this->configuration['modules'][$name]['default'];
//         return $isDefault;
//     }
    
//     /**
//      * Do not allow user to get instance by "new".
//      * 
//      * @return void
//      */
//     protected function __construct() {}

    
//     /**
//      * Get module configuration file path
//      * 
//      * @return string
//      */
//     protected function getModuleConfigFilePath() {
//         $basePath = X::system()->getBasePath();
//         $configPath = str_replace('/', DIRECTORY_SEPARATOR, sprintf('%s/config/modules.ini', $basePath));
//         return $configPath;
//     }
    

    

    

}