<?php
namespace X\Module\Account\Action\Login;
/**
 * 
 */
use X\Core\X;
/**
 * 
 */
class Qq extends \X\Util\Action\Basic { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( ) {
        /* @var $QQService \X\Service\QQ\Service */
        $QQService = X::system()->getServiceManager()->get(\X\Service\QQ\Service::getServiceName());
        $loginURL = $QQService->getConnect()->getLoginUrl();
        $this->gotoURL($loginURL);
    }
}