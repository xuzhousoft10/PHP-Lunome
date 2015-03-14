<?php
/**
 * 
 */
namespace X\Module\Lunome\Action\User\Chat;
/**
 * 
 */
use X\Module\Lunome\Util\Action\Basic;

/**
 * 
 */
class Stop extends Basic {
    /**
     * 
     */
    public function runAction( $id ) {
        $accountManager = $this->getUserService()->getAccount();
        
        if ( !$accountManager->hasFriend($id) ) {
            return;
        }
        
        $accountManager->unmarkChattingWithFriend($id);
        echo array('status'=>'1');
    }
}