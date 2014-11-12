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
        $accessToken = $this->getConfiguration()->get('AccessToken');
        $secretKey = $this->getConfiguration()->get('SecretKey');
        $this->OSS = new QiniuOSS($accessToken, $secretKey);
        $this->OSS->bucket = $this->getConfiguration()->get('DefaultBucket');
        $this->OSS->isPublic = $this->getConfiguration()->get('IsBucket');
        $this->putString('testishfaisjfhasdf', '1.txt');
    }
    
    /**
     * 上传文件到七牛OSS
     * @param string $file 本地文件路径。
     * @param string $path 目标文件路径， 默认为跟目录。
     * @param string $name 目标文件名， 默认与上传文件名相同。
     */
    public function putFile( $file, $path=null, $name=null ) {
        $this->OSS->putFile($file, $path, $name);
    }
    
    /**
     * @param unknown $content
     * @param unknown $path
     */
    public function putString( $content, $path ) {
        $this->OSS->putString($content, $path);
    }
    
    /**
     * @param unknown $path
     * @return string
     */
    public function getURL( $path ) {
        return $this->OSS->getURL($path);
    }
    
    /**
     * @param unknown $path
     * @return string
     */
    public function getContent( $path ) {
        return file_get_contents($this->getURL($path));
    }
}