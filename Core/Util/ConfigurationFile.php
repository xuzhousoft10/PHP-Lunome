<?php
/**
 *
 */
namespace X\Core\Util;

/**
 * 
 */
class ConfigurationFile extends ConfigurationArray  {
    /**
     * 该变量用来保存该配置文件的存储位置。
     * @var string
     */
    protected $path = null;
    
    /**
     * 初始化该配置类
     * @param string $path 配置文件的存储位置。
     */
    public function __construct( $path ) {
        if ( is_file($path) && is_readable($path) ) {
            $this->merge(require $path);
        } else {
            throw new Exception('Configuration file "'.$path.'" is not readable.');
        }
        $this->path = $path;
    }
    
    /**
     * 保存配置信息。如果没有更改则不会进行保存。
     * @return void
     */
    public function save() {
        if ( is_writable($this->path) ) {
            XUtil::storeArrayToPHPFile($this->path, $this->config);
        } else {
            throw new Exception('Unable to save configuration file to "'.$this->path.'", it\'s unwritable.');
        }
    }
}