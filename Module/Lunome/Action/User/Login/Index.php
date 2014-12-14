<?php
/**
 * The action file for account/login action.
 */
namespace X\Module\Lunome\Action\User\Login;

/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;

/**
 * The action class for account/login action.
 * @author Unknown
 */
class Index extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        $this->gotoURL('/index.php?module=lunome&action=user/login/qq');
    }
}