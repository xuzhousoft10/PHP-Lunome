<?php
/**
 * The action file for user/friend/index action.
 */
namespace X\Module\Lunome\Action\User\Friend;

/**
 * 
 */
use X\Module\Lunome\Util\Action\FriendManagement;

/**
 * The action class for user/friend/index action.
 * @author Unknown
 */
class Index extends FriendManagement { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        /* Find friends of current user. */
        $friends = $this->getUserService()->getFriend()->getAll();
        
        /* Load friend index view. */
        $name   = 'FRIEND_INDEX';
        $path   = $this->getParticleViewPath('User/Friend/Index');
        $option = array();
        $data   = array('friends'=>$friends);
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\FriendManagement::getActiveSettingItem()
     */
    protected function getActiveSettingItem() {
        return self::FRIEND_MENU_ITEM_LIST;
    }
}