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
     * @var unknown
     */
    private $customFormNeeded = false;
    
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
     * @param unknown $name
     * @param unknown $path
     */
    public function addFile( $name, $path ) {
        $this->parameters[$name] = curl_file_create($path);
    }
    
    /**
     * @var unknown
     */
    private $fileContents = array();
    
    /**
     * @param unknown $name
     * @param unknown $content
     */
    public function addFileString( $name, $fileName, $content ) {
        $this->fileContents[$name] = array('name'=>$fileName, 'content'=>$content);
        $this->customFormNeeded = true;
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
     * @var string
     */
    private $responseHeader = null;
    
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getPostParameters());
        
        $httpHeaders = $this->getHeaders();
        foreach ( $httpHeaders as $key => $value ) {
            $httpHeaders[$key] = "$key: $value";
        }
        if ( 0 < count($httpHeaders) ) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
        }
        
        $responseContent = curl_exec($ch);
        if ( 0 !== curl_errno($ch) ) {
            throw new Exception(curl_error($ch));
        }
        
        $this->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->responseType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);
        
        $responseContent = explode("\r\n\r\n", $responseContent);
        $this->responseContent = array_pop($responseContent);
        $this->responseHeader = array_pop($responseContent);
    }
    
    /**
     * @return multitype:
     */
    private function getPostParameters() {
        if ( !$this->customFormNeeded ) {
            return $this->parameters;
        }
        
        $data = array();
        $mimeBoundary = md5(microtime());
        
        foreach ( $this->parameters as $name => $val) {
            array_push($data, '--' . $mimeBoundary);
            array_push($data, "Content-Disposition: form-data; name=\"$name\"");
            array_push($data, '');
            array_push($data, $val);
        }
        
        foreach ($this->fileContents as $fieldName => $file) {
            array_push($data, '--' . $mimeBoundary);
            $mimeType = 'application/octet-stream';
            $fileName = Qiniu_escapeQuotes($file['name']);
            array_push($data, "Content-Disposition: form-data; name=\"{$fieldName}\"; filename=\"{$file['name']}\"");
            array_push($data, "Content-Type: $mimeType");
            array_push($data, '');
            array_push($data, $file['content']);
        }
        
        array_push($data, '--' . $mimeBoundary . '--');
        array_push($data, '');
        
        $body = implode("\r\n", $data);
        $contentType = 'multipart/form-data; boundary=' . $mimeBoundary;
        return array($contentType, $body);
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
        return json_decode($this->responseContent, true);
    }
}