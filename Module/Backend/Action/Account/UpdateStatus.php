<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Account;

/**
 * 
 */
use X\Module\Backend\Util\Action\Basic;

/**
 * 
 */
class UpdateStatus extends Basic {
    /**
     * 
     */
    public function runAction($account, $status) {
        $userService = $this->getUserService();
        $accountManager = $userService->getAccount();
        if ( !$accountManager->has($account) ) {
            $this->throw404();
        }
        $accountManager->updateStatus($account, $status);
        $this->goBack();
    }
}