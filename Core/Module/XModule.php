<?php
/**
 * 
 */
namespace X\Core\Module;

/**
 * 
 */
abstract class XModule extends \X\Core\Basic {
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
    public function getModulePath( $path='' ) {
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $moduleReflection = new \ReflectionClass($this);
        $basePath = dirname($moduleReflection->getFileName());
        $path = empty($path) ? $path : $basePath.DIRECTORY_SEPARATOR.$path;
        return $path;
    }
}