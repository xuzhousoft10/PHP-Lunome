<?php
/**
 * The action file for user/friend/index action.
 */
namespace X\Module\Lunome\Action\User\Friend;

/**
 * 
 */
use X\Module\Lunome\Util\Action\VisualMain;

/**
 * The action class for user/friend/index action.
 * @author Unknown
 */
class Index extends VisualMain { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        $this->activeMenuItem(self::MENU_ITEM_FRIEND);
        
        /* Find friends of current user. */
        $friends = $this->getUserService()->getFriend()->getAll();
        
        /* Load friend index view. */
        $name   = 'FRIEND_INDEX';
        $path   = $this->getParticleViewPath('User/Friend/Index');
        $option = array();
        $data   = array('friends'=>$friends);
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}