<?php
namespace X\Service\QiNiu\Core;
class HttpClient {
    /**
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
     * @var array
     */
    private $parameters = array();
    
    /**
     * @param unknown $name
     * @param unknown $value
     */
    public function addParameter( $name, $value ) {
        $this->parameters[$name] = $value;
    }
    
    /**
     * @var string
     */
    private $responseContent = null;
    
    /**
     * @var number
     */
    private $responseCode = null;
    
    /**
     * @var string
     */
    private $responseType = null;
    
    /**
     * 
     */
    public function post() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_USERAGENT, $this->getUserAgent());
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getBody());
        
        $httpHeaders = $this->getHeaders();
        foreach ( $httpHeaders as $key => $value ) {
            $httpHeaders[$key] = "$key: $value";
        }
        if ( 0 < count($httpHeaders) ) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
        }
        
        $this->responseContent = curl_exec($ch);
        if ( 0 !== curl_errno($ch) ) {
            throw new Exception(curl_error($ch));
        }
        
        $this->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->responseType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);
        
        $responseArray = explode("\r\n\r\n", $this->response);
        $responseArraySize = sizeof($responseArray);
        $respHeader = $responseArray[$responseArraySize-2];
        $respBody = $responseArray[$responseArraySize-1];
        
        list($reqid, $xLog) = getReqInfo($respHeader);
        
        $resp = new \Qiniu_Response($code, $respBody);
        $resp->Header['Content-Type'] = $contentType;
        $resp->Header["X-Reqid"] = $reqid;
        return array($resp, null);
    }
    
    /**
     * @var string
     */
    private $userAgent = null;
    
    /**
     * @return string
     */
    public function getUserAgent() {
        if ( null === $this->userAgent ) {
            $sdkVersion = QiniuOSS::SDK_VERSION;
            $system = php_uname('s');
            $machineType = php_uname('m');
            $phpVer = phpversion();
            $ua = "QiniuPHP/$sdkVersion ($system/$machineType) PHP/$phpVer";
            $this->userAgent = $ua;
        }
        return $this->userAgent;
    }
    
    /**
     * @var array
     */
    private $headers = array();
    
    /**
     * @return array
     */
    public function getHeaders() {
        return $this->headers;
    }
    
    /**
     * @var string
     */
    private $body = '';
    
    /**
     * @return string
     */
    public function getBody() {
        return $this->body;
    }
    
    /**
     * 
     */
    public function readJSON() {
        
    }
}