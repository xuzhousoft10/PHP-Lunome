<?php
/**
 * The action file for account/oauth/view action.
 */
namespace X\Module\Backend\Action\Account\Oauth;

/**
 * 
 */
use X\Core\X;
use X\Module\Backend\Util\Action\Visual;
use X\Module\Lunome\Service\User\Service as UserService;

/**
 * The action class for account/oauth/view action.
 * @author Unknown
 */
class View extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id ) {
        $this->setActiveItem(self::MENU_ACCOUNT_MANAGEMENT);
        
        /* @var $userService \X\Module\Lunome\Service\User\Service */
        $userService = X::system()->getServiceManager()->get(UserService::getServiceName());
        $oauth = $userService->getAccount()->getOauth($id);
        
        $name   = 'OAUTH_VIEW';
        $path   = $this->getParticleViewPath('Account/Oauth/View');
        $option = array();
        $data   = array('oauth'=>$oauth, 'returnURL'=>$this->getReferer());
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}