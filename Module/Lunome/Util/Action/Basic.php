<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action;
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
        $this->checkLoginRequirement();
    }
}