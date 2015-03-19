<?php
namespace X\Module\Account\Action\Notification;
/**
 * 
 */
use X\Module\Lunome\Util\Action\Basic;
/**
 * The action class for user/friend/index action.
 * @author Unknown
 */
class Close extends Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id ) {
        $notification = $this->getCurrentAccount()->getNotificationManager()->get($id);
        if ( null !== $notification ) {
            $notification->close();
        }
        echo json_encode(array('status'=>1));
    }
}