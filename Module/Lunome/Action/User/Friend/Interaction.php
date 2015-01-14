<?php
/**
 * The action file for user/friend/index action.
 */
namespace X\Module\Lunome\Action\User\Friend;

/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\FriendManagement;

/**
 * The action class for user/friend/index action.
 * @author Unknown
 */
class Interaction extends FriendManagement { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        $friends = $this->getUserService()->getAccount()->getFriends();
                
        $name   = 'INTERACTION_WITH_FRIENDS';
        $path   = $this->getParticleViewPath('User/Friend/Interaction');
        $option = array();
        $data   = array('friends'=>$friends);
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        $this->getView()->title = '集体互动';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\FriendManagement::getActiveSettingItem()
     */
    protected function getActiveSettingItem() {
        return self::FRIEND_MENU_ITEM_INTERACTION;
    }
}