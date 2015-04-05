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
        
        $isDebug = X::system()->getConfiguration()->get('is_debug', true);
        if ( $isDebug ) {
            $account = $accountService->get('00000000-0000-0000-0000-000000000000');
            $account->login();
        }
        
        if ( null === $accountService->getCurrentAccount() ) {
            $this->gotoURL('/index.php?module=account&action=login/qq');
        } else {
            $this->gotoURL('index.php');
        }
    }
}