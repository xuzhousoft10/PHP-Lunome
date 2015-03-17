<?php
namespace X\Module\Account\Action\Login;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 * 
 */
class Index extends Visual { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( ) {
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        
        if ( null === $accountService->getCurrentAccount() ) {
            $this->gotoURL('/index.php?module=account&action=login/qq');
        } else {
            $this->gotoURL('index.php');
        }
    }
}