<?php
/**
 * 
 */
namespace X\Core\Module;

/**
 * 
 */
use X\Core\X;
use X\Core\Util\XUtil;
use X\Core\Util\Configuration;
use X\Core\Util\Management;

/**
 * 
 */
class ModuleManagement extends Management {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\Management::init()
     */
    protected function init() {
        $path = X::system()->getPath('Config/modules.php');
        $this->configuration = new Configuration($path);
    }
    
    /**
     * 启动管理器
     * @return void
     */
    public function start() {
        $this->loadModulesFromModuleDir();
    }
    
    /**
     * 该变量保存着当前管理器的额配置信息。
     * @var \X\Core\Util\Configuration
     */
    protected $configuration = null;
    
    /**
     * 获取当前的配置信息
     * @return \X\Core\Util\Configuration
     */
    public function getConfiguration() {
        return $this->configuration;
    }
    
    
    /**
     * 该变量保存着所有已加载的模块。
     * @var \X\Core\Module\XModule[]
     */
    protected $modules = array();
    
    /**
     * 该变量保存当前框架的默认模块的名称。
     * @var string
     */
    protected $defaultModuleName = null;
    
    /**
     * 加载所有可用的模块， 如果名称以"."开头， 则忽略该模块。
     * @return void
     */
    protected function loadModulesFromModuleDir() {
        $modulePath = X::system()->getPath('Module');
        $moduleNames = scandir($modulePath);
        foreach ( $moduleNames as $moduleName ) {
            if ( '.' == $moduleName[0] ) { continue; }
            $this->loadModule($moduleName);
        }
    }
    
    /**
     * 根据模块名称将模块加载到框架中。
     * @param string $name 模块的名称。
     * @return boolean
     */
    protected function loadModule($name) {
        if ( !isset($this->configuration[$name]) || !$this->configuration[$name]['enable'] ) {
            $this->modules[$name]['available'] = false;
            $this->modules[$name]['is_loaded'] = false;
            $this->modules[$name]['module'] = null;
            return false;
        }
        
        if ( $this->isDefault($name) ) {
            $this->defaultModuleName = $name;
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
     * 根据传入框架的参数执行制定的模块， 如果没有指定， 则会执行默认的模块。
     * @return void
     */
    public function run() {
        $parameters = X::system()->getParameters();
        $moduleName = isset($parameters['module']) ? $parameters['module'] : $this->defaultModuleName;
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
     * 获取框架中所有有效模块的名称。
     * @return array
     */
    public function getList() {
        return array_keys($this->modules);
    }
    
    /**
     * 检查指定名称的模块是否存在。
     * @param string $moduleName
     * @return boolean
     */
    public function has( $moduleName ) {
        return isset($this->modules[$moduleName]);
    }
    
    /**
     * 从管理器中根据模块名称获取模块。 如果模块没有被加载， 则会加载该模块。
     * @param string $name 要获取的模块名称。
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
    
    /**
     * 检查模块是否被启用。
     * @param string $name 检查模块的名字
     * @return boolean
     */
    public function isEnable( $name ) {
        return isset($this->configuration[$name]) && $this->configuration[$name]['enable'];
    }
    
    /**
     * 根据名称启用指定模块
     * @param string $name 要启用的模块的名称。
     * @return void
     */
    public function enable( $name ) {
        if ( !$this->getConfiguration()->has($name) ) {
            throw new Exception(sprintf('Module "%s" does not exists.', $name));
        }
        
        $this->getConfiguration()->merge($name, array('enable'=>true));
        $this->getConfiguration()->save();
        $this->loadModule($name);
    }
    
    /**
     * 根据名称停用用指定模块
     * @param string $name 要停用的模块的名称。
     * @return void
     */
    public function disable( $name ) {
        if ( !$this->has($name) ) {
            throw new Exception(sprintf('Module "%s" does not exists.', $name));
        }
        
        $this->configuration[$name]['enable'] = false;
        $this->getConfiguration()->save();
    }
    
    /**
     * 根据名称创建新的模块。
     * @param string $name 新模块的名称。
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
        $this->configuration[$moduleName] = array('enable'=>false);
        $this->getConfiguration()->save();
    }
    
    /**
     * 将指定名称的模块设置为默认模块。
     * @param string $name 要设置为默认模块的名称。
     * @throws Exception
     */
    public function setDefault( $name = null ) {
        if ( !is_null(null) &&  !$this->has($name) ) {
            throw new Exception(sprintf('Can not find module "%s".', $name));
        }
        
        foreach ( $this->configuration as $moduleName => $moduleConfig ) {
            $this->configuration[$moduleName]['default'] = false;
        }
        $this->defaultModuleName = null;
        
        if ( !is_null($name) ) {
            $this->configuration[$name]['default'] = true;
            $this->defaultModuleName = $name;
        }
        $this->getConfiguration()->save();
    }
    
    /**
     * 检查指定模块是否为默认模块。
     * @param string $name
     * @return boolean
     */
    public function isDefault( $name ) {
        return isset($this->configuration[$name]['default']) &&  $this->configuration[$name]['default'];
    }
    
    /**
     * 根据名称删除指定模块。
     * @param string $name
     */
    public function delete( $name ) {
        $name = ucfirst($name);
        $path = X::system()->getPath("Module/$name");
        XUtil::deleteFile($path);
        
        unset($this->configuration[$name]);
        $this->getConfiguration()->save();
        unset($this->modules[$name]);
    }
    
    /**
     * 为指定模块创建迁移脚本。
     * @param string $module 模块名称
     * @param string $name 迁移脚本名称
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
        $content[] = '<?php';
        $content[] = "/** \n * Migration file for $name \n */";
        $content[] = sprintf('namespace X\\Module\\%s\\Migration;', $moduleName);
        $content[] = '';
        $content[] = "/** \n * $migrationClassName \n */";
        $content[] = sprintf('class %s extends \\X\\Core\\Module\\Migrate {', $migrationClassName);
        $content[] = "    /** \n     * (non-PHPdoc)\n     * @see \\X\\Core\\Module\\InterfaceMigrate::up()\n     */";
        $content[] = '    public function up() {';
        $content[] = '        /*@TODO: Add your migration up code here.*/';
        $content[] = '    }';
        $content[] = '';
        $content[] = "    /** \n     * (non-PHPdoc)\n     * @see \\X\\Core\\Module\\InterfaceMigrate::down()\n     */";
        $content[] = '    public function down() {';
        $content[] = '        /*@TODO: Add your migration down code here. */';
        $content[] = '    }';
        $content[] = '}';
        $content = implode("\n", $content);
        $migrationClassPath = X::system()->getPath(sprintf('Module/%s/Migration/%s.php', $moduleName, $migrationClassName));
        file_put_contents($migrationClassPath, $content);
        
        /* Return the migration path. */
        return $migrationClassPath;
    }
    
    /**
     * 根据名称为指定模块执行迁移脚本。
     * @param string $name
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
            if ( '.' === $migration[0] ) {
                continue;
            }
            
            $className = basename($migration, '.php');
            $classFullName = $namespace.'\\'.$className;
            $migrationObject = new $classFullName();
            $migrationObject->up();
            $history[] = $migration;
            XUtil::storeArrayToPHPFile($historyPath, $history);
        }
    }
    
    /**
     * 回滚指定模块的迁移。
     * @param string $name
     * @param integer $stepCount
     * @throws Exception
     */
    public function migrateDown( $name, $stepCount ) {
        $moduleName = ucfirst($name);
        if ( !$this->has($moduleName) ) {
            throw new Exception(sprintf('Can not find module "%s".', $moduleName));
        }
        
        /* Get migration file list. */
        $historyPath = X::system()->getPath(sprintf('Module/%s/Migration/History.php', $moduleName));
        $history = is_file($historyPath) ? require $historyPath : array();
        
        $namespace = sprintf('\\X\\Module\\%s\\Migration', $moduleName);
        /* Execute the up action */
        while ( 0 < $stepCount ) {
            $migration = array_pop($history);
            if ( is_null($migration) ) {
                break;
            }
            $className = basename($migration, '.php');
            $classFullName = $namespace.'\\'.$className;
            $migrationObject = new $classFullName();
            $migrationObject->down();
            XUtil::storeArrayToPHPFile($historyPath, $history);
        }
    }
}