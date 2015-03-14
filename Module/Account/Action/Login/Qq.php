<?php
/**
 * The action file for user/login/qq action.
 */
namespace X\Module\Lunome\Action\User\Login;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Service\User\Service;

/**
 * The action class for user/login/qq action.
 * @author Unknown
 */
class Qq extends \X\Util\Action\Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        $url = $this->getService(Service::getServiceName())->getQQLoginURL();
        header("Location:$url");
        X::system()->stop();
    }
}