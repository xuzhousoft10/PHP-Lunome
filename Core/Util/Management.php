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
abstract class Management extends Basic {
    /**
     * 该变量保存着所有Management的实例。
     *
     * @var ServiceManagement
     */
    protected static $managers = null;
    
    /**
     * 获取Management的实例。
     *
     * @return \X\Core\Util\Management
     */
    public static function getManager() {
        $manager = get_called_class();
        if ( !isset(self::$managers[$manager]) ) {
            self::$managers[$manager] = new $manager();
        }
        
        return self::$managers[$manager];
    }
    
    /**
     * 将构造函数不公开， 以防止框架内存在第二个管理实例。
     */
    protected function __construct() {}
    
    /**
     * 启动该管理器
     */
    public function start() {}
    
    /**
     * 结束该管理器
     */
    public function stop() {
        self::$managers[get_class($this)] = null;
        unset(self::$managers[get_class($this)]);
    }
}