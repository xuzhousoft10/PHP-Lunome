<?php
/**
 *
 */
namespace X\Library\QQ\Connect;

/**
 * 
 */
class Request {
    /**
     * 当前请求的参数列表。
     * @var array
     */
    private $parameters = array();
    
    /**
     * 请求目标URL
     * @var string
     */
    private $url = null;
    
    /**
     * 当前请求的请求方法。
     * @var integer
     */
    private $method = self::METHOD_GET;
    
    /**
     * 当前请求的其他配置信息
     * @var array
     */
    private $config = array();
    
    /**
     * 初始化当前请求
     * @param string $url   目标URL
     * @param array $parms  参数列表
     * @param integer $method 请求方法
     * @param array $config 其他配置
     */
    public function __construct( $url, $parms=array(), $method=self::METHOD_GET, $config=array() ) {
        $this->url          = $url;
        $this->parameters   = $parms;
        $this->method       = $method;
        $this->config       = $config;
    }
    
    /**
     * 将当前请求转换成字符串。
     * @return string
     */
    public function toString() {
        if ( self::METHOD_GET === $this->method ) {
            $parms = array();
            foreach ( $this->parameters as $key=>$value ) {
                $parms[] = sprintf('%s=%s', $key, $value);
            }
            $connector = ( false === strpos($this->url, '?') ) ? '?' : '&';
            $url = $this->url.$connector.implode('&', $parms);
            return $url;
        } else {
            return $this->url;
        }
    }
    
    const METHOD_GET    = 1;
    const METHOD_POST   = 2;
}