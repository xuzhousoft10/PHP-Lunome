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
class AnswerToBeFriendRequest extends Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $request, $notification, $result,  $message ) {
        $userService = $this->getUserService();
        $accountManager = $userService->getAccount();
        
        if ( !$accountManager->hasToBeFriendRequest($request) ) {
            return;
        }
        
        if ( !$userService->hasNotification($notification) ) {
            return;
        }
        
        $isAgreed = (1===$result*1);
        $view = 'User/Friend/NotificationToBeFriend'.($isAgreed ? 'Yes' : 'No');
        $accountManager->updateToBeFriendRequestAnswer($request, $isAgreed, $message, $view);
        $userService->closeNotification($notification);
    }
}