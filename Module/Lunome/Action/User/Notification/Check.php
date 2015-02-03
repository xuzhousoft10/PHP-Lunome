<?php
/**
 * The action file for user/friend/index action.
 */
namespace X\Module\Lunome\Action\User\Notification;

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
        $userService = $this->getUserService();
        $count = $userService->countUnclosedNotification();
        echo json_encode(array('count'=>$count));
    }
}