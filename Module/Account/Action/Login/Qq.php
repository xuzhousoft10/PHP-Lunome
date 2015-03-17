<?php
namespace X\Module\Account\Action\Login;
/**
 * 
 */
use X\Core\X;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 * 
 */
class Qq extends \X\Util\Action\Basic { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( ) {
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        if ( null !== $accountService->getCurrentAccount() ) {
            $this->gotoURL('index.php');
        }
        
        /* @var $QQService \X\Service\QQ\Service */
        $QQService = X::system()->getServiceManager()->get(\X\Service\QQ\Service::getServiceName());
        $loginURL = $QQService->getConnect()->getLoginUrl();
        $this->gotoURL($loginURL);
    }
}