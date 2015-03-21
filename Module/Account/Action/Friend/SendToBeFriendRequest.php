<?php
namespace X\Module\Account\Action\Friend;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 * The action class for user/friend/index action.
 * @author Unknown
 */
class SendToBeFriendRequest extends Basic { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $recipient, $message ) {
        $moduleConfig = $this->getModule()->getConfiguration();
        $friendMaxCount = intval($moduleConfig->get('user_friend_max_count'));
        $friendManager = $this->getCurrentAccount()->getFriendManager();
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        if ( $friendManager->count() > $friendMaxCount 
        || !$accountService->exists($recipient)
        || $friendManager->isFriendWith($recipient) ) {
            return;
        }
        
        $view = $this->getModule()->getPath('View/Particle/Friend/NotificationToBeFriend.php');
        $this->getCurrentAccount()->getFriendManager()->sendToBeFriendRequest($recipient, $message, $view);
        echo json_encode(array('status'=>true));
    }
}