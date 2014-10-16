<?php
/**
 * The action file for account/login action.
 */
namespace X\Module\Lunome\Action\User\Login;

/**
 * 
 */
use X\Module\Lunome\Util\Action\Visual;

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
        $this->getView()->loadLayout($this->getLayoutViewPath('Blank'));
        $this->getView()->removeParticle('INDEX_NAV_BAR');
        
        $name   = 'USER_LOGIN';
        $path   = $this->getParticleViewPath('User/Login');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}