<?php
/**
 * The action file for account/freeze action.
 */
namespace X\Module\Backend\Action\Account\Admin;

/**
 * 
 */
use X\Core\X;
use X\Module\Backend\Util\Action\Basic;
use X\Module\Lunome\Service\User\Service as UserService;

/**
 * The action class for account/freeze action.
 * @author Unknown
 */
class Delete extends Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id ) {
        /* @var $userService \X\Module\Lunome\Service\User\Service */
        $userService = X::system()->getServiceManager()->get(UserService::getServiceName());
        $userService->getAccount()->deleteAdmin($id);
        $this->goBack();
    }
}