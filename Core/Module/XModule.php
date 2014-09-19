<?php
/**
 * Namespace Defination
 */
namespace X\Core\Module;

/**
 * The module base class, which all the modules should extens
 * from it.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @since   Version 0.0.0
 */
abstract class XModule extends \X\Core\Basic {
    /**
     * The name of the module.
     * 
     * @var string
     */
    protected $name = null;
    
    /**
     * Get the name of module.
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Initiate the module.
     * 
     * @param string $name The name of the module.
     */
    public function __construct( $name ) {
        $this->init();
        $this->name = $name;
    }
    
    /**
     * 
     */
    protected function init() {}
    
    /**
     * Execute the module.
     * 
     * @param array $parameters
     * 
     * @return void
     */
    abstract public function run($parameters=array());
    
    /**
     * Get the path of current module or subpath of it if $path is not empty.
     * @param string $path The subpath of the module.
     * @return string
     */
    public function getModulePath( $path='' ) {
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $moduleReflection = new \ReflectionClass($this);
        $basePath = dirname($moduleReflection->getFileName());
        $path = empty($path) ? $path : $basePath.DIRECTORY_SEPARATOR.$path;
        return $path;
    }
    
//     /**
//      * Process the installation of this module.
//      * The would find the "install.php" in the start path 
//      * of the module.
//      * 
//      * @param $string $message The value to store the error message.
//      * 
//      * @return boolean
//      */
//     public function install(&$message=null) {
//         if ( $this->isInstalled() ) {
//             $message=sprintf('"%s" already been installed.', get_class($this));
//             return false;
//         }
        
//         $startPath = $this->getStartPath();
        
//         $installFile = $startPath.DIRECTORY_SEPARATOR.'install.php';
//         if ( is_file($installFile) ) {
//             require $installFile;
//         } else {
//             $message = sprintf('"%s" can not be found.', $installFile);
//             return false;
//         }
        
//         $historyFile = $this->getStartHistoryFilePath();
//         $historyContent = sprintf("; %s:Installed\n", date('Y-m-d H:i:s', time()));
//         $isOK = file_put_contents($historyFile, $historyContent);
//         $isOK ? null : $message = sprintf('"%s" can not be installed.', get_class($this));
//         $isOK ? null : error_log(sprintf('"%s" can not be created.', $historyFile));
//         return !(false === $isOK);
//     }
    
//     /**
//      * Process the uninstalltion of this module.
//      * This method would try to execute the "uninstall.php" in
//      * the start folder.
//      * 
//      * @param string $message The value to store the error message. if there is no error,
//      * this value would not be changed.
//      * 
//      * @throws Exception
//      */
//     public function uninstall( &$message=null ) {
//         if ( !$this->isInstalled() ) {
//             $message = sprintf('"%s" has not been installed.', get_class($this));
//             return false;
//         }
        
//         $startPath = $this->getStartPath();
//         $uninstallFile = $startPath.DIRECTORY_SEPARATOR.'uninstall.php';
//         if ( is_file($uninstallFile) ) {
//             require $uninstallFile;
//         } else {
//             $message = sprintf('"%s" can not be counf.', $uninstallFile);
//             return false;
//         }
        
//         $historyFile = $this->getStartHistoryFilePath();
//         $isOK = unlink($historyFile);
//         $isOK ? null : $message = sprintf('"%s" can not be uninstalled.', get_class($this));
//         $isOK ? null : error_log(sprintf('"%s" can not be deleted.', $historyFile));
//         return $isOK;
//     }
    
//     /**
//      * Do update for this module.
//      * Execute all the scripts under upgrade folder.
//      * 
//      * @param string $message The value to store the message, if there is no error, then this value
//      * would not be changed.
//      * @param array $list The value to store the upgrade item.
//      * 
//      * @return boolean
//      */
//     public function upgrade( &$message=null, &$list=array() ) {
//         if ( !$this->isInstalled() ) {
//             $message = sprintf('"%s" has not been installed.', get_class($this));
//             return false;
//         }
        
//         $history = Ini::read($this->getStartHistoryFilePath());
//         $historyData = $history->getData();
//         $upgradeScripts = $this->getUpgradeScriptList();
//         foreach ( $upgradeScripts as $name => $upgradeScript ) {
//             if ( isset($historyData[$name]) ) {
//                 continue;
//             }
            
//             if ( is_file($upgradeScript) ) {
//                 require $upgradeScript;
//             } else {
//                 $message = sprintf('Upgrade file "%s" can not be found.', $upgradeScript);
//                 return false;
//             }
//             $history->addItem($name, date('Y-m-d H:i:s', time()));
//             $list[] = $name;
//         }
//         $history->save();
//         return true;
//     }
    
//     /**
//      * Check if upgrade available.
//      * 
//      * @return boolean
//      */
//     public function isUpgradeAvailable() {
//         $historyData = $this->getStartHistoryFilePath();
//         if ( is_file($historyData) ) {
//             $historyData = Ini::read($historyData);
//             $historyData = $historyData->getData();
//             $historyData = array_keys($historyData);
//         } else {
//             $historyData = array();
//         }
//         $upgradeScripts = $this->getUpgradeScriptList();
//         $upgradeScripts = array_keys($upgradeScripts);
//         $diff = array_diff($upgradeScripts, $historyData);
//         return 0 < count($diff);
//     }
    
//     /**
//      * Add patch file to the module.
//      * 
//      * @param string $file The path of patch file.
//      * 
//      * @return boolean
//      */
//     public function addPatch( $file ) {
//         $path = $this->getStartPath().'/upgrade';
//         if ( !is_dir($path) ) {
//             mkdir($path);
//         }
//         $path = $path.'/'.basename($file);
//         copy($file, $path);
//         return true;
//     }
    
//     /**
//      * Get wheterh is installed of this module.
//      *
//      * @return boolean
//      */
//     public function isInstalled() {
//         return is_file($this->getStartHistoryFilePath());
//     }
    
//     /**
//      * Return the upgrade script list.
//      * 
//      * @return array
//      */
//     protected function getUpgradeScriptList() {
//         $startPath = $this->getStartPath();
//         $upgradeFolder = $startPath.DIRECTORY_SEPARATOR.'upgrade'.DIRECTORY_SEPARATOR;
//         if ( !is_dir($upgradeFolder) ) {
//             return array();
//         }
        
//         $upgradeList = array();
//         foreach ( glob($upgradeFolder.'*.php') as $upgradeScript ) {
//             $name = basename($upgradeScript, '.php');
//             $upgradeList[$name] = $upgradeScript;
//         }
        
//         return $upgradeList;
//     }
    
//     /**
//      * Get the start history file path of this module.
//      * 
//      * @return string
//      */
//     protected function getStartHistoryFilePath() {
//         return $this->getModulePath('history');
//     }
    
//     /**
//      * Get start path of module.
//      * 
//      * @return string
//      */
//     protected function getStartPath() {
//         return $this->getModulePath('start');
//     }
}