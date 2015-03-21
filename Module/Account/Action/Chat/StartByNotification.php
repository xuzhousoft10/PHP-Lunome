<?php
namespace X\Module\Account\Action\Chat;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Basic;
/**
 * 
 */
class StartByNotification extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $id ) {
        $currentAccount = $this->getCurrentAccount();
        $notification = $currentAccount->getNotificationManager()->get($id);
        if ( null === $notification ) {
            $this->throw404();
        }
        
        $message = $notification->getData();
        $notification->close();
        $this->gotoURL('/?module=account&action=chat/index', array('friend'=>$message['writer_id']));
    }
}