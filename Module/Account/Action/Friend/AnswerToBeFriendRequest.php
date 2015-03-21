<?php
namespace X\Module\Account\Action\Friend;
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
        $currentAccount = $this->getCurrentAccount();
        $friendManager = $currentAccount->getFriendManager();
        $request = $friendManager->getToBeFriendRequest($request);
        
        if ( null === $request ) {
            return;
        }
        
        $notification = $currentAccount->getNotificationManager()->get($notification);
        if ( null === $notification ) {
            return;
        }
        
        $isAgreed = (1===$result*1);
        $view = $this->getModule()->getPath('View/Particle/Friend/NotificationToBeFriend'.($isAgreed ? 'Yes' : 'No').'.php');
        if ( $isAgreed ) {
            $request->agree($message, $view);
        } else {
            $request->refuse($message, $view);
        }
        
        $notification->close();
    }
}