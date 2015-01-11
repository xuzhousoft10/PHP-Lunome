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
class Close extends Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id ) {
        $userService = $this->getUserService();
        $count = $userService->closeNotification($id);
        echo json_encode(array('status'=>1));
    }
}