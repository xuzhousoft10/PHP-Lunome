<?php
/**
 * The action file for user/login/qqcallback action.
 */
namespace X\Module\Lunome\Action\User\Login;

/**
 *
 */
use X\Core\X;
use X\Module\Lunome\Service\User\Service;

/**
 * The action class for user/login/qqcallback action.
 * @author Unknown
 */
class Weibocallback extends \X\Util\Action\Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $code ) {
        $callback = "http://{$_SERVER['HTTP_HOST']}/index.php?module=lunome&action=user/login/weibocallback";
        $this->getService(Service::getServiceName())->loginByWeiboCallback($code, $callback);
        $this->gotoURL('/index.php');
    }
}