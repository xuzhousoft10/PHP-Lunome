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
    public function runAction( $page=1 ) {
        $this->setActiveItem(self::MENU_ACCOUNT_MANAGEMENT);
        
        /* @var $userService \X\Module\Lunome\Service\User\Service */
        $userService = X::system()->getServiceManager()->get(UserService::getServiceName());
        $accounts = $userService->getAccount()->findAll(null, ($page-1)*20, 20);
        $currentUser = $userService->getCurrentUser();
        
        /* Load account index view */
        $name   = 'ACCOUNT_INDEX';
        $path   = $this->getParticleViewPath('Account/Index');
        $option = array();
        $data   = array('accounts'=>$accounts, 'currentUser'=>$currentUser);
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        /* Load pager view */
        $count = $userService->getAccount()->count();
        $name   = 'UTIL_PAGER';
        $path   = $this->getParticleViewPath('Util/Pager');
        $option = array();
        $data   = array('total'=>$count, 'current'=>$page, 'size'=>20, 'url'=>'/index.php?module=backend&action=account/index&page=%d');
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}