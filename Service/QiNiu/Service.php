<?php
/**
 * This file implements the service Movie
 */
namespace X\Service\QiNiu;

/**
 * 
 */
use X\Service\QiNiu\Core\QiniuOSS;

/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
    /**
     * @var QiniuOSS
     */
    private $OSS = null;
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::afterStart()
     */
    protected function afterStart() {
        $this->OSS = new QiniuOSS();
        $this->OSS->bucket = $this->getConfiguration()->get('DefaultBucket');
        
        $path = $this->getPath('Core/rs.php');
        require $path;
        $accessToken = $this->getConfiguration()->get('AccessToken');
        $secretKey = $this->getConfiguration()->get('SecretKey');
        Qiniu_SetKeys($accessToken, $secretKey);
        $client = new \Qiniu_MacHttpClient(null);
        $result = Qiniu_RS_Stat($client, 'lunome', 'cover_001.jpg');
        
        $this->putFile(__FILE__);
    }
    
    /**
     * 上传文件到七牛OSS
     * @param string $file 本地文件路径。
     * @param string $path 目标文件路径， 默认为跟目录。
     * @param string $name 目标文件名， 默认与上传文件名相同。
     */
    public function putFile( $file, $path=null, $name=null ) {
        require_once($this->getPath('Core/io.php'));
        $this->OSS->putFile($file, $path, $name);
    }
}