<?php
/**
 * 
 */
namespace X\Module\Lunome\Action\User\Chat;
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
     * 
     */
    public function runAction( $id ) {
        $userService = $this->getUserService();
        if ( !$userService->hasNotification($id) ) {
            $this->throw404();
        }
        
        $notification = $userService->getNotification($id);
        $userService->closeNotification($id);
        $message = $notification['sourceData'];
        $userService->getAccount()->cleanIsUnreadMotificationSendedMark($message['writer_id']);
        $this->gotoURL('/?module=lunome&action=user/chat/index', array('friend'=>$message['writer_id']));
    }
}