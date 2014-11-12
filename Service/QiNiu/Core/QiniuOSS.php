<?php
namespace X\Service\QiNiu\Core;
class QiniuOSS {
    /**
     * @var string
     */
    private $accessToken = '';
    
    /**
     * @var string
     */
    private $secretKey = '';
    
    /**
     * @param unknown $acceccToken
     * @param unknown $secretKey
     */
    public function __construct($acceccToken, $secretKey) {
        $this->accessToken = $acceccToken;
        $this->secretKey = $secretKey;
    }
    
    /**
     * 当前正在使用的bucket的名字
     * @var string
     */
    public $bucket = null;
    
    /**
     * 上传文件到七牛OSS
     * @param string $file 本地文件路径。
     * @param string $path 目标文件路径， 默认为跟目录。注意， 不是目标文件名
     * @param string $name 目标文件名， 默认与上传文件名相同。
     * @param array $config 上传所需的其他配置参数。
     */
    public function putFile( $localFile, $targetPath=null, $targetName=null, $config=array() ) {
        /* 获取上传凭证 */
        $token = $this->getUploadToken($config);
        
        /* 整理文件名等信息 */
        $targetName = (null === $targetName) ? basename($localFile) : $targetName;
        $targetPath = (null === $targetPath) ? $targetName : $targetPath.'/'.$targetName;
        
        /* 进行文件上传 */
        $client = new HttpClient(self::UPLOAD_HOST);
        $client->addParameter('token', $token);
        $client->addFile('file', $localFile);
        $client->addParameter('key', $targetPath);
        $client->post();
        $result = $client->readJSON();
        return $result;
    }
    
    /**
     * @param unknown $content
     * @param unknown $path
     */
    public function putString( $content, $path ) {
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'rs.php';
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'http.php';
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'io.php';
        
        
        $upToken=$this->getUploadToken();
        $key = $path; 
        $body = $content; 
        $putExtra = null;
        global $QINIU_UP_HOST;
        
        if ($putExtra === null) {
            $putExtra = new \Qiniu_PutExtra();
        }
        
        $fields = array('token' => $upToken);
        if ($key === null) {
            $fname = '?';
        } else {
            $fname = $key;
            $fields['key'] = $key;
        }
        if ($putExtra->CheckCrc) {
            $fields['crc32'] = $putExtra->Crc32;
        }
        if ($putExtra->Params) {
            foreach ($putExtra->Params as $k=>$v) {
                $fields[$k] = $v;
            }
        }
        
        $files = array(array('file', $fname, $body, $putExtra->MimeType));
        
        $client = new Qiniu_HttpClient();
        list($contentType, $body) = $this->Qiniu_Build_MultipartForm($fields, $files);
        $result = Qiniu_Client_CallWithForm($client, $QINIU_UP_HOST, $body, $contentType);
        
        
    }
    
    function Qiniu_Build_MultipartForm($fields, $files) // => ($contentType, $body)
    {
        $data = array();
        $mimeBoundary = md5(microtime());
    
        foreach ($fields as $name => $val) {
            array_push($data, '--' . $mimeBoundary);
            array_push($data, "Content-Disposition: form-data; name=\"$name\"");
            array_push($data, '');
            array_push($data, $val);
        }
    
        foreach ($files as $file) {
            array_push($data, '--' . $mimeBoundary);
            list($name, $fileName, $fileBody, $mimeType) = $file;
            $mimeType = empty($mimeType) ? 'application/octet-stream' : $mimeType;
            $fileName = Qiniu_escapeQuotes($fileName);
            array_push($data, "Content-Disposition: form-data; name=\"$name\"; filename=\"$fileName\"");
            array_push($data, "Content-Type: $mimeType");
            array_push($data, '');
            array_push($data, $fileBody);
        }
    
        array_push($data, '--' . $mimeBoundary . '--');
        array_push($data, '');
    
        $body = implode("\r\n", $data);
        $contentType = 'multipart/form-data; boundary=' . $mimeBoundary;
        return array($contentType, $body);
    }
    
    /**
     * @param unknown $config
     * @see http://developer.qiniu.com/docs/v6/api/reference/security/upload-token.html
     * @return string
     */
    private function getUploadToken( $config=array() ) {
        $parameters = array();
        $parameters['scope'] = $this->bucket;
        foreach ( $config as $name => $value ) {
            $parameters[$name] = $value;
        }
        if ( !isset($parameters['deadline']) ) {
            $parameters['deadline'] = 3600;
        }
        $parameters['deadline'] += time();
        $sign = json_encode($parameters);
        $encodedSign = $this->urlSafeBase64Encode($sign);
        $sign = hash_hmac('sha1', $encodedSign, $this->secretKey, true);
        $sign = $this->urlSafeBase64Encode($sign);
        $sign = $this->accessToken.':'.$sign.':'.$encodedSign;
        return $sign;
    }
    
    /**
     * @param unknown $str
     * @return mixed
     */
    private function urlSafeBase64Encode($string) {
        return str_replace(array('+', '/'), array('-', '_'), base64_encode($string));
    }
    
    /**
     * @param unknown $path
     */
    public function getURL( $path ) {
        $domain = $this->getDomain();
        $path = str_replace('%2F', '/', rawurlencode($path));
        $url = "http://$domain/$path";
        if ( !$this->isPublic ) {
            $deadline = time()+3600;
            $url .= "?e=$deadline";
            $toekn = hash_hmac('sha1', $url, $this->secretKey, true);
            $toekn = $this->accessToken.':'.$this->urlSafeBase64Encode($toekn);
            $url = "$url&token=$toekn";
        }
        return $url;
    }
    
    public $isPublic = false;
    
    /**
     * @var string
     */
    private $domain = null;
    
    /**
     * @param unknown $domain
     */
    public function setDomain( $domain ) {
        $this->domain = $domain;
    }
    
    /**
     * @return string
     */
    public function getDomain() {
        if ( null === $this->domain ) {
            $this->domain = $this->bucket.'.qiniudn.com';
        }
        return $this->domain;
    }
    
    /**
     * @var string
     */
    const UPLOAD_HOST = 'http://upload.qiniu.com';
    
    /**
     * @var string
     */
    const SDK_VERSION = '6.1.9';
}

class Qiniu_HttpClient
{
    public function RoundTrip($req) // => ($resp, $error)
    {
        return Qiniu_Client_do($req);
    }
}