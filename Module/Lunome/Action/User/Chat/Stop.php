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
class Stop extends Basic {
    /**
     * 
     */
    public function runAction( $id ) {
        $this->getUserService()->getAccount()->unmarkChattingWithFriend($id);
        echo array('status'=>'1');
    }
}