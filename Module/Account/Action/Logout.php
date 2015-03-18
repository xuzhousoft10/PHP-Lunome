<?php
namespace X\Module\Account\Action;
/**
 * 
 */
use X\Core\X;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 * The action class for user/logout action.
 * @author Unknown
 */
class Logout extends \X\Util\Action\Basic { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( ) {
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        $currentAccount = $accountService->getCurrentAccount();
        if ( null !== $currentAccount ) {
            $currentAccount->logout();
        }
        $this->gotoURL('/index.php');
    }
}