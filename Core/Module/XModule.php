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
}