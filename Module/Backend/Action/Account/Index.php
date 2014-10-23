<?php
/**
 * The action file for account/index action.
 */
namespace X\Module\Backend\Action\Account;

/**
 * 
 */
use X\Core\X;
use X\Module\Backend\Util\Action\Index as IndexAction;
use X\Module\Lunome\Service\User\Service as UserService;

/**
 * The action class for account/index action.
 * @author Unknown
 */
class Index extends IndexAction { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        /* @var $userService \X\Module\Lunome\Service\User\Service */
        $userService = X::system()->getServiceManager()->get(UserService::getServiceName());
        $accounts = $userService->getAccount()->getAll();
        
        /* Load account index view */
        $name   = 'ACCOUNT_INDEX';
        $path   = $this->getParticleViewPath('Account/Index');
        $option = array();
        $data   = array('accounts'=>$accounts);
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}