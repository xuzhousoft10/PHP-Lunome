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
        $client->addParameter('file', curl_file_create($localFile));
        $client->addParameter('key', $targetPath);
        $client->postFile();
        $result = $client->readJSON();
        return $result;
    }
    
    /**
     * @param unknown $config
     * @see http://developer.qiniu.com/docs/v6/api/reference/security/upload-token.html
     * @return string
     */
    private function getUploadToken( $config ) {
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