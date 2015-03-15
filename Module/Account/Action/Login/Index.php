<?php
namespace X\Module\Account\Action\Login;
/**
 * 
 */
use X\Module\Lunome\Util\Action\Visual;
/**
 * 
 */
class Index extends Visual { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( ) {
        $this->gotoURL('/index.php?module=account&action=login/qq');
    }
}