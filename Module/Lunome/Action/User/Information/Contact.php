<?php
/**
 * The action file for user/information/basic action.
 */
namespace X\Module\Lunome\Action\User\Information;

/**
 * Use statements
 */
use X\Service\XView\Core\Handler\Html;
use X\Module\Lunome\Util\Action\UserInfoSetting;

/**
 * The action class for user/information/basic action.
 * @author Unknown
 */
class Contact extends UserInfoSetting { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        /* Load layout. */
        $this->getView()->loadLayout(Html::LAYOUT_TWO_COLUMNS);
        
        /* Load information menu. */
        $name   = 'INFORMATION_BASIC';
        $path   = $this->getParticleViewPath('User/Information/Contact');
        $option = array('zone'=>'right');
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}