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
class Weibo extends \X\Util\Action\Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        $callback = "http://{$_SERVER['HTTP_HOST']}/index.php?module=lunome&action=user/login/weibocallback";
        $url = $this->getService(Service::getServiceName())->getWeiboLoginURL($callback);
        header("Location:$url");
        X::system()->stop();
    }
}