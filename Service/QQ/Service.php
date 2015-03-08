<?php
/**
 * This file implements the service Movie
 */
namespace X\Service\QQ;

/**
 * 
 */
use X\Service\QQ\Core\Connect\SDK as ConnectSDK;

/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
    /**
     * @var unknown
     */
    protected static $serviceName = 'QQ';
    
    /**
     * @var \X\Service\QQ\Core\Connect\SDK
     */
    private $connect = null;
    
    /**
     * @return \X\Service\QQ\Core\Connect\SDK
     */
    public function getConnect() {
        if ( null === $this->connect ) {
            $configuration = $this->getConfiguration();
            ConnectSDK::$appid      = $configuration['QQConnect']['appid'];
            ConnectSDK::$appkey     = $configuration['QQConnect']['appkey'];
            ConnectSDK::$callback   = $configuration['QQConnect']['callback'];
            $this->connect = new ConnectSDK();
        }
        return $this->connect;
    }
}