<?php
namespace X\Module\Account\Action\Chat;
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
        $currentAccount = $this->getCurrentAccount();
        $friendManager = $currentAccount->getFriendManager();
        $content = trim($content);
        if ( empty($content) || !$friendManager->isFriendWith($friend) ) {
            return;
        }
        
        $chatManager = $friendManager->get($friend)->getChatManager();
        $notificationView = $this->getModule()->getPath('View/Particle/Chat/NotificationUnread.php');
        $message = $chatManager->send($content, $notificationView);
        
        $name   = 'USER_WRITE';
        $path   = $this->getParticleViewPath('Chat/ISay');
        $option = array();
        $data   = array('record'=>$message, 'myInformation'=>$currentAccount->getProfileManager());
        $view = $this->loadParticle($name, $path, $option, $data);
        $view->display();
    }
}