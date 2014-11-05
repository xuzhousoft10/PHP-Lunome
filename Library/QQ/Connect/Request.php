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
    public function __construct( $url, $parms=array(), $config=array() ) {
        $this->url          = $url;
        $this->parameters   = $parms;
        $this->config       = $config;
    }
    
    /**
     * 将当前请求转换成字符串。
     * @return string
     */
    public function toString() {
        $parms = array();
        foreach ( $this->parameters as $key=>$value ) {
            $parms[] = sprintf('%s=%s', $key, $value);
        }
        $connector = ( false === strpos($this->url, '?') ) ? '?' : '&';
        $url = $this->url.$connector.implode('&', $parms);
        return $url;
    }
    
    /**
     * 执行GET请求并返回请求结果。并解析结果。
     * @param string $format
     * @return mixed
     */
    public function get( $format='' ) {
        $combined = $this->toString();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $combined);
        $response =  curl_exec($ch);
        curl_close($ch);
        
        $handler = sprintf('formatResponse%s', $format);
        if ( method_exists($this, $handler) ) {
            return $this->$hander($response);
        } else {
            return $response;
        }
    }
    
    /**
     * 将结果由json解析到数组。
     * @param string $response
     * @return array
     */
    private function formatResponseJSON( $response ) {
        return json_decode($response, true);
    }
    
    /**
     * 将结果解析到URL参数数组。
     * @param string $response
     * @return multitype:
     */
    private function formatResponseURLParam( $response ) {
        $params = array();
        parse_str($response, $params);
        return $params;
    } 
    
    const FOTMAT_JSON       = 'JSON';
    const FORMAT_URL_PARAM  = 'URLParam';
    
    const METHOD_GET    = 1;
    const METHOD_POST   = 2;
}