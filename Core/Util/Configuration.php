<?php
/**
 *
 */
namespace X\Core\Util;

/**
 * 
 */
use X\Core\Basic;

/**
 * 
 */
class Configuration extends Basic implements \ArrayAccess, \Iterator  {
    /**
     * 初始化该配置类
     * @param string $path 配置文件的存储位置。
     */
    public function __construct( $path ) {
        $this->path = $path;
        if ( !is_file($this->path) ) {
            $this->config = array();
        }
    }
    
    /**
     * 该变量保存着配置文件所有配置。
     * @var array
     */
    protected $config = null;
    
    /**
     * 该变量用来标记当前配置有没有被改变。
     * @var boolean
     */
    protected $isDirty = false;
    
    /**
     * 该变量用来保存该配置文件的存储位置。
     * @var string
     */
    protected $path = null;
    
    /**
     * 将配置文件加载到当前项目中， 如果已经加载， 则不会重新加载。
     * @return void
     */
    protected function load() {
        if ( null !== $this->config ) {
            return;
        }
        $this->config = require $this->path;
    }
    
    /**
     * 根据名称设置配置项目的值，名称可以使用"."进行级别分割。
     * 如果项目不存在则会新建该项目。
     * 
     * @param string $name 配置项目名称
     * @param string $value 配置项目值
     * @return void
     */
    public function set( $name, $value ) {
        $this->load();
        $items = explode('.', $name);
        $item = &$this->config;
        
        while ( 0 < count($items) ) {
            $item = &$item[array_shift($items)];
        }
        $item = $value;
        $this->isDirty = true;
    }
    
    /**
     * 将新的配置信息合并到当前配置中
     * @param string $name
     * @param array $value
     */
    public function merge( $name, array $value ) {
        $this->config[$name] = array_merge($this->config[$name], $value);
    }
    
    /**
     * 根据名称获取配置项目值。 名称可以使用"."进行级别分割。
     * 如果项目不存在则会返回null。
     *
     * @param string $name 配置项目名称
     * @return mixed
     */
    public function get( $name ) {
        $this->load();
        $items = explode('.', $name);
        $item = $this->config;
        while ( 0 < count($items) ) {
            $itemName = array_shift($items);
            if ( !isset($item[$itemName]) ) {
                $item = null;
                break;
            } else {
                $item = $item[$itemName];
            }
        }
        return $item;
    }
    
    /**
     * 检查一个配置项目是否存在。
     * @return boolean
     */
    public function has($name) {
        $this->load();
        $items = explode('.', $name);
        $item = $this->config;
        while ( 0 < count($items) ) {
            $itemName = array_shift($items);
            if ( !isset($item[$itemName]) ) {
                return false;
            } else {
                $item = $item[$itemName];
            }
        }
        return true;
    }
    
    /**
     * 获取当前的所有配置信息。
     *
     * @return array
     */
    public function getAll() {
        $this->load();
        return $this->config;
    }
    
    /**
     * 使用数组替换当前配置信息。 这将完全覆盖原先的配置信息。
     *
     * @param array $config
     */
    public function setAll( $config ) {
        $this->load();
        $this->config = $config;
        $this->isDirty = true;
    }
    
    /**
     * 保存配置信息。如果没有更改则不会进行保存。
     *
     * @return void
     */
    public function save() {
        if ( !$this->isDirty ) {
            return;
        }
        
        XUtil::storeArrayToPHPFile($this->path, $this->config);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Iterator::current()
     */
    public function current() {
        $this->load();
        return current($this->config);
    }

    /**
     * (non-PHPdoc)
     * @see Iterator::next()
     */
    public function next() {
        $this->load();
        return next($this->config);
    }

    /**
     * (non-PHPdoc)
     * @see Iterator::key()
     */
    public function key() {
        $this->load();
        return key($this->config);
    }

    /** 
     * (non-PHPdoc)
     * @see Iterator::valid()
     */
    public function valid() {
        $this->load();
        return $this->offsetExists(key($this->config));
    }
    
    /**
     * (non-PHPdoc)
     * @see Iterator::rewind()
     */
    public function rewind() {
        $this->load();
        return reset($this->config);
    }

    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset) {
        $this->load();
        return isset($this->config[$offset]);
    }
    
    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset) {
        $this->load();
        return $this->config[$offset];
    }
    
    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value) {
        $this->load();
        if (null === $offset ) {
            $this->config[] = $value;
        } else {
            $this->config[$offset] = $value;
        }
        $this->isDirty = true;
    }
    
    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset) {
        $this->load();
        unset($this->config[$offset]);
        $this->isDirty = true;
    }
}