<?php
/**
 * This file implements the service Movie
 */
namespace X\Service\Sina;

/**
 * 
 */
use X\Service\Sina\Core\Connect\SDK as ConnectSDK;

/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
    /**
     * @var \X\Service\Sina\Core\Connect\SDK
     */
    private $connect = null;
    
    /**
     * @return \X\Service\Sina\Core\Connect\SDK
     */
    public function getConnect() {
        if ( null === $this->connect ) {
            $this->connect = new ConnectSDK();
            $this->connect->AppID = $this->getConfiguration()->get('Connect.AppKey');
            $this->connect->AppSecret = $this->getConfiguration()->get('Connect.AppSecret');
        }
        return $this->connect;
    }
}