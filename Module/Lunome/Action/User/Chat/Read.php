<?php
/**
 *
 */
namespace X\Module\Lunome\Action\User\Chat;

/**
 * 
 */
use X\Module\Lunome\Util\Action\Visual;

/**
 * 
 */
class Read extends Visual {
    /**
     * @param unknown $friend
     */
    public function runAction( $friend ) {
        $userService = $this->getUserService();
        if ( !$userService->getAccount()->hasFriend($friend) ) {
            return;
        }
        
        $messages = $userService->getAccount()->getUnreadChatMessages($friend);
        $friendInformation = $userService->getAccount()->getInformation($friend);
        
        $name   = 'USER_FRIEND_SAYS';
        $path   = $this->getParticleViewPath('User/Chat/FriendSays');
        $option = array();
        $data   = array('messages'=>$messages, 'friendInformation'=>$friendInformation);
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->displayParticle($name);
    }
}