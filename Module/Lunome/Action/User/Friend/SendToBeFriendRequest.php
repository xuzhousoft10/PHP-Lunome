<?php
/**
 * The action file for user/friend/index action.
 */
namespace X\Module\Lunome\Action\User\Friend;

/**
 * 
 */
use X\Module\Lunome\Util\Action\Basic;

/**
 * The action class for user/friend/index action.
 * @author Unknown
 */
class SendToBeFriendRequest extends Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $recipient, $message ) {
        $accountManager = $this->getUserService()->getAccount();
        $moduleConfig = $this->getModule()->getConfiguration();
        $friendMaxCount = intval($moduleConfig->get('user_friend_max_count'));
        
        if ( $accountManager->countFriends() > $friendMaxCount 
        || $accountManager->hasFriend($recipient) 
        || !$accountManager->has($recipient) ) {
            return;
        }
        
        $view = 'User/Friend/NotificationToBeFriend';
        $accountManager->sendToBeFriendRequest($recipient, $message, $view);
        echo json_encode(array('status'=>true));
    }
}