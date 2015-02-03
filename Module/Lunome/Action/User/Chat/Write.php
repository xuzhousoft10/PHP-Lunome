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
class Write extends Visual {
    /**
     * @param unknown $friend
     */
    public function runAction( $friend, $content ) {
        $userService = $this->getUserService();
        $accountManager = $userService->getAccount();
        $content = trim($content);
        
        if ( empty($content) || !$accountManager->hasFriend($friend) ) {
            return;
        }
        
        $notificationView = 'User/Chat/NotificationUnread';
        $record = $userService->getAccount()->sendChatMessage($friend, $content, $notificationView);
        $myInformation = $userService->getAccount()->getInformation();
        
        $name   = 'USER_WRITE';
        $path   = $this->getParticleViewPath('User/Chat/ISay');
        $option = array();
        $data   = array('record'=>$record, 'myInformation'=>$myInformation);
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->displayParticle($name);
    }
}