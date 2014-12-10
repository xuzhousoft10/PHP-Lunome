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
     * @var \X\Service\QQ\Core\Connect\SDK
     */
    private $connect = null;
    
    /**
     * @return \X\Service\QQ\Core\Connect\SDK
     */
    public function getConnect() {
        if ( null === $this->connect ) {
            ConnectSDK::$appid      = $this->getConfiguration()->get('QQConnect.appid');
            ConnectSDK::$appkey     = $this->getConfiguration()->get('QQConnect.appkey');
            ConnectSDK::$callback   = $this->getConfiguration()->get('QQConnect.callback');
            $this->connect = new ConnectSDK();
        }
        return $this->connect;
    }
}