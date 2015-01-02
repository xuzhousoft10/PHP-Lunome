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
        $view = 'User/Friend/NotificationToBeFriend';
        $accountManager = $this->getUserService()->getAccount();
        $accountManager->sendToBeFriendRequest($recipient, $message, $view);
        echo json_encode(array('status'=>true));
    }
}