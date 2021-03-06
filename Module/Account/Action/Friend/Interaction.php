<?php
namespace X\Module\Account\Action\Friend;
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
        $moduleConfig = $this->getModule()->getConfiguration();
        $friends = $this->getCurrentAccount()->getFriendManager()->find();
                
        $name   = 'INTERACTION_WITH_FRIENDS';
        $path   = $this->getParticleViewPath('Friend/Interaction');
        $option = array();
        $data   = array('friends'=>$friends, 'peopleCount'=>$moduleConfig->get('user_interaction_max_friend_count'));
        $this->loadParticle($name, $path, $option, $data);
        
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