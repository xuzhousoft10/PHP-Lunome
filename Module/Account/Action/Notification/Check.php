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
class Check extends Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction() {
        $account = $this->getCurrentAccount()->getNotificationManager()->count();
        echo json_encode(array('count'=>$account));
    }
}