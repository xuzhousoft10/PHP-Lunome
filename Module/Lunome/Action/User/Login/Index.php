<?php
/**
 * The action file for account/login action.
 */
namespace X\Module\Lunome\Action\User\Login;

/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Lunome\Service\Configuration\Service as ConfigService;

/**
 * The action class for account/login action.
 * @author Unknown
 */
class Index extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        $this->getView()->title = "登录 | Lunome";
        $this->getView()->loadLayout($this->getLayoutViewPath('Blank'));
        $this->getView()->removeParticle('INDEX_NAV_BAR');
        /* @var $configService ConfigService */
        $configService = X::system()->getServiceManager()->get(ConfigService::getServiceName());
        
        $name   = 'USER_LOGIN';
        $path   = $this->getParticleViewPath('User/Login');
        $option = array();
        $data   = array(
            'backgroundImage'  => $configService->get('login_background_image'),
            'QQLoginIcon'     => $configService->get('qq_login_icon'), 
        );
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}