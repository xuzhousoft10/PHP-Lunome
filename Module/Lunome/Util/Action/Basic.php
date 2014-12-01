<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action;

/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Service\User\Service as UserService;

/**
 * Basic action class
 * 
 * @method \X\Module\Lunome\Service\User\Service getUserService()
 * @method \X\Module\Lunome\Service\Movie\Service getMovieService()
 */
abstract class Basic extends \X\Util\Action\Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Action::beforeRunAction()
     */
    protected function beforeRunAction() {
        $isGuest = $this->getService(UserService::getServiceName())->getIsGuest();
        if ( $isGuest ) {
            $this->gotoURL('/index.php?module=lunome&action=user/login/index');
            X::system()->stop();
        }
    }
}