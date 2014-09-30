<?php
/**
 * This file defines the module management class.
 */
namespace X\Core\Module;

/**
 * Use statements
 */
use X\Core\X;
use X\Core\Util\XUtil;

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
    protected $configuration = array(
        /* Module config informations */
        'modules' => array(),
        /* The module name of default module. */
        'defaultModule' => null,
    );
    
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
        
        if ( $this->isDefault($name) ) {
            $this->configuration['defaultModule'] = $name;
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
        $moduleName = isset($parameters['module']) ? $parameters['module'] : $this->configuration['defaultModule'];
        if ( is_null($moduleName) ) {
            throw new Exception('Can not find any module to execute.');
        }
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
    /**
     * List all modules in the system.
     * 
     * @return array
     */
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
    
    /**
     * Check if the module exists by given name.
     * 
     * @param string $moduleName
     * @return boolean
     */
    public function has( $moduleName ) {
        return isset($this->modules[$moduleName]);
    }
    
    /**
     * Create a new module by given name.
     * 
     * @param string $name The name of new module.
     * @throws Exception
     */
    public function create( $name ) {
        $moduleName = ucfirst($name);
        if ( $this->has($moduleName) ) {
            throw new Exception(sprintf('Module "%s" has already exists.', $name));
        }
        
        /* Create module folders and readme text file. */
        $basePath = X::system()->getPath('Module/'.$moduleName);
        mkdir($basePath);
        $actionPath = X::system()->getPath('Module/'.$moduleName.'/Action');
        mkdir($actionPath);
        file_put_contents($actionPath.DIRECTORY_SEPARATOR.'readme.txt', 'This folder contains all actions.');
        $viewPath = X::system()->getPath('Module/'.$moduleName.'/View');
        mkdir($viewPath);
        file_put_contents($viewPath.DIRECTORY_SEPARATOR.'readme.txt', 'This folder contains all views.');
        
        /* Create module file */
        $moduleFile = array();
        $moduleFile[] = '<?php';
        $moduleFile[] = sprintf('namespace X\\Module\\%s;', $moduleName);
        $moduleFile[] = 'class Module extends \\X\\Core\\Module\\XModule {';
        $moduleFile[] = '    /**';
        $moduleFile[] = '     * (non-PHPdoc)';
        $moduleFile[] = '     * @see \X\Core\Module\XModule::run()';
        $moduleFile[] = '     */';
        $moduleFile[] = '    public function run($parameters = array()) {';
        $moduleFile[] = '        /* @TODO: Input your own code here. */';
        $moduleFile[] = sprintf('        echo "The module \"%s\" has been created.";', $moduleName);
        $moduleFile[] = '    }';
        $moduleFile[] = '}';
        $moduleFile = implode("\n", $moduleFile);
        $moduleFilePath = $basePath.DIRECTORY_SEPARATOR.'Module.php';
        file_put_contents($moduleFilePath, $moduleFile);
        
        /* Update module management configurations. */
        $this->configuration['modules'][$moduleName] = array('enable'=>false);
        $this->saveConfigurations();
        $this->loadModule($moduleName);
    }
    
    /**
     * Set the module as default module by given name.
     * if $name is null, then all the modules would not be default module.
     * 
     * @param string $name
     * @throws Exception
     */
    public function setDefault( $name = null ) {
        if ( !is_null(null) &&  !$this->has($name) ) {
            throw new Exception(sprintf('Can not find module "%s".', $name));
        }
        
        foreach ( $this->configuration['modules'] as $moduleName => $moduleConfig ) {
            $this->configuration['modules'][$moduleName]['default'] = false;
        }
        $this->configuration['defaultModule'] = null;
        
        if ( !is_null($name) ) {
            $this->configuration['modules'][$name]['default'] = true;
            $this->configuration['defaultModule'] = $name;
        }
        
        $this->saveConfigurations();
    }
    
    /**
     * Check if a module is default module.
     * 
     * @param string $name
     * @return boolean
     */
    public function isDefault( $name ) {
        return isset($this->configuration['modules'][$name]['default']) &&  $this->configuration['modules'][$name]['default'];
    }
    
    /**
     * Delete the module by given name.
     * 
     * @param unknown $name
     */
    public function delete( $name ) {
        $name = ucfirst($name);
        $path = X::system()->getPath("Module/$name");
        $this->deleteFolder($path);
        
        unset($this->configuration['modules'][$name]);
        $this->saveConfigurations();
        unset($this->modules[$name]);
    }
    
    /**
     * Save the configuration file into fs.
     * @return void
     */
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
    
    /**
     * Delete path and his subfiles.
     * @param unknown $path
     * @return boolean
     */
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
    
    /**
     * Create a module migration by given name.
     * 
     * @param unknown $name
     */
    public function migrateCreate( $module, $name ) {
        $moduleName = ucfirst($module);
        if ( !$this->has($moduleName) ) {
            throw new Exception(sprintf('Can not find module "%s".', $moduleName));
        }
        
        /* Create migration folder */
        $path = X::system()->getPath(sprintf('Module/%s/Migration', $moduleName));
        if ( !is_dir($path) ) {
            mkdir($path);
        }
        
        /* Generate the migraion class name. */
        $hasHistory = file_exists(X::system()->getPath(sprintf('Module/%s/Migration/History.php', $moduleName)));
        $migrationCount = count(scandir($path));
        $migrationCount -= 2;
        if ( $hasHistory ) {
            $migrationCount --;
        }
        $migrationClassName = sprintf('M%05d_%s', $migrationCount, $name);
        
        /* Generate the migration file */
        $content = array();
        $content[] = '<?';
        $content[] = "/** \n * Migration file for $name \n */";
        $content[] = sprintf('namespace X\\Module\\%s\\Migration;', $moduleName);
        $content[] = '';
        $content[] = "/** \n * $migrationClassName \n */";
        $content[] = sprintf('class %s extends \\X\\Core\\Module\\Migrate {', $migrationClassName);
        $content[] = "    /** \n     * (non-PHPdoc)\n     * @see \\X\\Core\\Module\\InterfaceMigrate::up()\n     */";
        $content[] = '    public function up() {';
        $content[] = '        /*@TODO: Add your migration code here.*/';
        $content[] = '    }';
        $content[] = '';
        $content[] = "    /** \n     * (non-PHPdoc)\n     * @see \\X\\Core\\Module\\InterfaceMigrate::down()\n     */";
        $content[] = '    public function down() {';
        $content[] = '        /*@TODO: Add your migration code here. */';
        $content[] = '    }';
        $content[] = '}';
        $content = implode("\n", $content);
        $migrationClassPath = X::system()->getPath(sprintf('Module/%s/Migration/%s.php', $moduleName, $migrationClassName));
        file_put_contents($migrationClassPath, $content);
    }
    
    /**
     * Execute the up action for migration.
     * 
     * @param unknown $name
     */
    public function  migrateUp( $name ) {
        $moduleName = ucfirst($name);
        if ( !$this->has($moduleName) ) {
            throw new Exception(sprintf('Can not find module "%s".', $moduleName));
        }
        
        /* Get migration file list. */
        $migrationPath = X::system()->getPath(sprintf('Module/%s/Migration', $moduleName));
        $files = scandir($migrationPath);
        $historyPath = X::system()->getPath(sprintf('Module/%s/Migration/History.php', $moduleName));
        $history = is_file($historyPath) ? require $historyPath : array();
        /* Remove history item from migrate list. */
        $migrations = array_diff($files, $history, array('.', '..', 'History.php'));
        
        $namespace = sprintf('\\X\\Module\\%s\\Migration', $moduleName);
        /* Execute the up action */
        foreach ( $migrations as $index => $migration ) {
            $className = basename($migration, '.php');
            $classFullName = $namespace.'\\'.$className;
            $migrationObject = new $classFullName();
            $migrationObject->up();
            $history[] = $migration;
            XUtil::storeArrayToPHPFile($historyPath, $history);
        }
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