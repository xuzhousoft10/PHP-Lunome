<?php
/**
 *
*/
namespace X\Module\Backend\Action\Account;

/**
 *
 */
use X\Module\Backend\Util\Action\Basic;
use X\Module\Lunome\Model\Account\AccountModel;

/**
 *
 */
class UpdateRole extends Basic {
    /**
     *
     */
    public function runAction($account, $role, $do) {
        $userService = $this->getUserService();
        $accountManager = $userService->getAccount();
        $role = (int)$role;
        
        if ( AccountModel::RL_NORMAL_ACCOUNT === $role ) {
            $this->goBack();
        }
        
        if ( !$accountManager->has($account) ) {
            $this->throw404();
        }
        
        if ( 'add' === $do ) {
            $accountManager->addRole($account, $role);
        } else {
            $accountManager->removeRole($account, $role);
        }
        $this->goBack();
    }
}