<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Backend\Util\Action;

/**
 * 
 */
use X\Core\X;

/**
 * Visual action class
 * 
 * @method \X\Module\Lunome\Service\User\Service getUserService()
 * @method \X\Module\Lunome\Service\Movie\Service getMovieService()
 * @method \X\Module\Lunome\Service\Tv\Service getTvService()
 */
abstract class Basic extends \X\Util\Action\Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Action::beforeRunAction()
     */
    protected function beforeRunAction() {
        $isGuest = $this->getUserService()->getIsGuest();
        if ( $isGuest ) {
            $this->gotoURL('/index.php?module=lunome&action=user/login/index');
            X::system()->stop();
        }
        parent::beforeRunAction();
    }
}