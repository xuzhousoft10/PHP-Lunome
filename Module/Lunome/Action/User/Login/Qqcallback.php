<?php
/**
 * The action file for user/login/qqcallback action.
 */
namespace X\Module\Lunome\Action\User\Login;

/**
 *
 */
use X\Module\Lunome\Service\User\Service;

/**
 * The action class for user/login/qqcallback action.
 * @author Unknown
 */
class Qqcallback extends \X\Util\Action\Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        $this->getService(Service::getServiceName())->loginByQQCallBack();
        $this->gotoURL('/index.php');
    }
}