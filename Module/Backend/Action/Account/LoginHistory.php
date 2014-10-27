<?php
/**
 * The action file for account/index action.
 */
namespace X\Module\Backend\Action\Account;

/**
 * 
 */
use X\Core\X;
use X\Module\Backend\Util\Action\Visual;

/**
 * The action class for account/index action.
 * @author Unknown
 */
class LoginHistory extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id, $page=1 ) {
        $this->setActiveItem(self::MENU_ACCOUNT_MANAGEMENT);
        
        /* @var $userService \X\Module\Lunome\Service\User\Service */
        $userService = $this->getService('User');
        $history = $userService->getAccount()->getLoginHistory($id, ($page-1)*20, 20);
        
        /* Load account index view */
        $name   = 'LOGIN_HISTORY_INDEX';
        $path   = $this->getParticleViewPath('Account/LoginHistory');
        $option = array();
        $data   = array('history'=>$history);
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        /* Load pager view */
        $pagerUrl = sprintf('/index.php?module=backend&action=account/loginHistory&id=%s&page=%%d',$id);
        $count = $userService->getAccount()->getLoginHistoryCount($id);
        $name   = 'UTIL_PAGER';
        $path   = $this->getParticleViewPath('Util/Pager');
        $option = array();
        $data   = array('total'=>$count, 'current'=>$page, 'size'=>20, 'url'=>$pagerUrl);
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}