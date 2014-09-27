<?php
/**
 * The action file for user/information/basic action.
 */
namespace X\Module\Lunome\Action\Security;

/**
 * Use statements
 */
use X\Service\XView\Core\Handler\Html;
use X\Module\Lunome\Util\Action\SecuritySetting;

/**
 * The action class for user/information/basic action.
 * @author Unknown
 */
class Password extends SecuritySetting { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        /* Load layout. */
        $this->getView()->loadLayout(Html::LAYOUT_TWO_COLUMNS);
        
        /* Load information menu. */
        $name   = 'INFORMATION_BASIC';
        $path   = $this->getParticleViewPath('Security/Password');
        $option = array('zone'=>'right');
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}