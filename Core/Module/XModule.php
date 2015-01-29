<?php
/**
 * 
 */
namespace X\Core\Module;

/**
 * 
 */
use X\Core\Basic;
use X\Core\Util\Configuration;

/**
 * 
 */
abstract class XModule extends Basic {
    /**
     * 该变量保存当前模块的名称。
     * @var string
     */
    protected $name = null;
    
    /**
     * 获取当前模块的名称。
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * 构造当前模块。
     * @param string $name 模块名称
     */
    public function __construct( $name ) {
        $this->init();
        $this->name = $name;
    }
    
    /**
     * 初始化当前模块， 当你自定义一个新的模块时，你可以重写该方法
     * 以初始化新定义的模块。
     */
    protected function init() {}
    
    /**
     * 运行该模块， 当你自定义一个新模块时， 你比需实现该方法。
     * @param string $parameters
     */
    abstract public function run($parameters=array());
    
    /**
     * 快捷当时获取当前模块下的文件或者目录的路径。
     * @return string
     */
    public function getPath( $path=null ) {
        $module = new \ReflectionClass(get_class($this));
        $modulePath = dirname($module->getFileName());
        $path = (null===$path) ? $modulePath : $modulePath.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $path);
        return $path;
    }
    
    private $configurations = array();
    
    /**
     * @param string $name
     * @return \X\Core\Util\Configuration
     */
    public function getConfiguration( $name='main' ) {
        $name = ucfirst($name);
        if ( !isset($this->configurations[$name]) ) {
            $configPath = $this->getPath('Config/'.$name.'.php');
            $this->configurations[$name] = new Configuration($configPath);
        }
        return $this->configurations[$name];
    }
}