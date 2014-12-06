<?php
/**
 * 
 */
namespace X\Core\Util;

/**
 * 
 */
class HttpRequest {
    /**
     * 请求的URL
     * @var string
     */
    public $url = null;
    
    /**
     * @param unknown $url
     */
    public function __construct( $url ) {
        $this->url = $url;
    }
    
    /**
     * 本次请求的参数列表。
     * --key : the name of parameter <br>
     * --value : the value of the parameter
     * @var array
     */
    private $parameters = array();
    
    /**
     * @param unknown $name
     * @param unknown $value
     */
    public function addParameter( $name, $value ) {
        $this->parameters[$name] = $value;
        return $this;
    }
    
    /**
     * @param unknown $parameters
     */
    public function setParameters( $parameters ) {
        $this->parameters = array_merge($this->parameters, $parameters);
        return $this;
    }
    
    /**
     * 
     */
    public function getGetURL() {
        $url = $this->url;
        $connector = (false === strpos($url, '?')) ? '?' : '&';
        $url = $this->url.$connector.http_build_query($this->parameters);
        return $url;
    }
    
    /**
     * @var unknown
     */
    private $responseText = null;
    
    /**
     * 
     */
    public function post() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->parameters);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        $this->responseText = curl_exec($ch);
        curl_close($ch);
    }
    
    /**
     * 
     */
    public function get() {
        $this->responseText = file_get_contents($this->getGetURL());
    }
    
    /**
     * @return array
     */
    public function readJson() {
        return json_decode($this->responseText, true);
    }
}