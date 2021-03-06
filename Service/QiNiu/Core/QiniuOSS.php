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
     * @return array
     */
    public function getList( $path=null, $offset=null, $length=null ) {
        $parameters = array();
        $parameters['bucket'] = $this->bucket;
        $parameters['marker'] = $offset;
        $parameters['limit'] = $length;
        $parameters['prefix'] = $path;
        $parameters['delimiter'] = '/';
        $parameters = array_filter($parameters);
        $url = self::MANAGEMENT_HOST.'/list?'.http_build_query($parameters);
        $token = $this->getManagementToken($url);
        
        $client = new HttpClient( $url );
        $client->addHeader('Authorization', 'QBox '.$token);
        $client->post();
        $result = $client->readJSON();
        return isset($result['items']) ? $result['items'] : array();
    }
    
    /**
     * @param unknown $path
     */
    public function stat( $path ) {
        $action = self::RESOURCE_MANAGEMENT_STAT;
        $path = $this->getEncodedEntryURI($path);
        return $this->doResourceManagement($action, $path);
    }
    
    /**
     * @param unknown $source
     * @param unknown $destination
     * @return Ambigous <\X\Service\QiNiu\Core\mixed, mixed>
     */
    public function copy( $source, $destination ) {
        $action = self::RESOURCE_MANAGEMENT_COPY;
        $source = $this->getEncodedEntryURI($source);
        $destination = $this->getEncodedEntryURI($destination);
        $this->doResourceManagement($action, $source, $destination);
    }
    
    /**
     * @param unknown $source
     * @param unknown $destination
     */
    public function move( $source, $destination ) {
        $action = self::RESOURCE_MANAGEMENT_MOVE;
        $source = $this->getEncodedEntryURI($source);
        $destination = $this->getEncodedEntryURI($destination);
        $this->doResourceManagement($action, $source, $destination);
    }
    
    /**
     * @param unknown $path
     */
    public function delete( $path ) {
        $action = self::RESOURCE_MANAGEMENT_DELETE;
        $path = $this->getEncodedEntryURI($path);
        $this->doResourceManagement($action, $path);
    }
    
    /**
     * @param unknown $path
     * @param unknown $mime
     */
    public function changeMimeType( $path, $mime ) {
        $action = self::RESOURCE_MANAGEMENT_CHGM;
        $path = $this->getEncodedEntryURI($path);
        $mime = $this->getEncodedEntryURI($mime);
        $this->doResourceManagement($action, $path, $mime);
    }
    
    /**
     * @param unknown $action
     * @return mixed
     */
    private function doResourceManagement( $action ) {
        $parameters = func_get_args();
        $query = call_user_func_array('sprintf', $parameters);
        $url = self::RESOURCE_HOST.$query;
        $token = $this->getManagementToken($url);
        
        $client = new HttpClient($url);
        $client->addHeader('Authorization', 'QBox '.$token);
        $client->post();
        $result = $client->readJSON();
        return $result;
    }
    
    /**
     * @param unknown $key
     * @return string
     */
    private function getEncodedEntryURI( $key ) {
        $entry = $this->bucket.':'.$key;
        $entry = $this->urlSafeBase64Encode($entry);
        return $entry;
    }
    
    /**
     * @param unknown $url
     * @param string $content
     * @return string
     */
    private function getManagementToken( $url, $content='' ) {
        $url = parse_url($url);
        $sign  = isset($url['path']) ? $url['path'] : '';
        $sign .= isset($url['query']) ? '?'.$url['query'] : '';
        $sign .= "\n";
        $sign .= empty($content) ? '' : $content;
        $sign = hash_hmac('sha1', $sign, $this->secretKey, true);
        $sign = $this->urlSafeBase64Encode($sign);
        $token = $this->accessToken.':'.$sign;
        return $token;
    }
    
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
        $client = new HttpClient(self::UPLOAD_HOST);
        $client->addParameter('token', $this->getUploadToken());
        $client->addParameter('key', $path);
        $client->addFileString('file', $path, $content);
        $client->post();
        $result = $client->readJSON();
        return $result;
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
    const MANAGEMENT_HOST = 'http://rsf.qbox.me';
    
    /**
     * @var unknown
     */
    const RESOURCE_HOST = 'http://rs.qbox.me';
    
    /**
     * @var string
     */
    const SDK_VERSION = '6.1.9';
    
    /**
     * @var string
     */
    const RESOURCE_MANAGEMENT_STAT      = '/stat/%s';
    
    /**
     * @var string
     */
    const RESOURCE_MANAGEMENT_COPY      = '/copy/%s/%s';
    
    /**
     * @var string
     */
    const RESOURCE_MANAGEMENT_MOVE      = '/move/%s/%s';
    
    /**
     * @var string
     */
    const RESOURCE_MANAGEMENT_DELETE    = '/delete/%s';
    
    /**
     * @var string
     */
    const RESOURCE_MANAGEMENT_CHGM      = '/chgm/%s/mime/%s';
}