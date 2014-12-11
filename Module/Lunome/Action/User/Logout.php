<?php
/**
 * The action file for user/logout action.
 */
namespace X\Module\Lunome\Action\User;

/**
 * 
 */
use X\Module\Lunome\Util\Action\Basic;

/**
 * The action class for user/logout action.
 * @author Unknown
 */
class Logout extends Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        $this->getUserService()->logout();
        $this->gotoURL('/index.php');
    }
}