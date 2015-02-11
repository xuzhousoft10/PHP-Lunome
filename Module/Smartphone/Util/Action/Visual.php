<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Smartphone\Util\Action;

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
abstract class Visual extends \X\Util\Action\Visual {
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeRunAction()
     */
    public function beforeRunAction() {
        parent::beforeRunAction();
        $isGuest = $this->getUserService()->getIsGuest();
        if ( $isGuest ) {
            $this->gotoURL('/index.php?module=lunome&action=user/login/index');
            X::system()->stop();
        }
        
        $view = $this->getView();
        $view->addMetaData('viewport', 'viewport', 'width=device-width, initial-scale=1');
        $view->loadLayout($this->getLayoutViewPath('Main'));
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        parent::beforeDisplay();
        $view = $this->getView();
        $assets = $this->getAssetsURL();
        
        $view->addCssLink('jquery.mobile', $assets.'/library/jquery.mobile/jquery.mobile-1.4.5.css');
        $view->addCssLink('jquery.mobile.icons', $assets.'/library/jquery.mobile/jquery.mobile.icons-1.4.5.css');
        
        $view->addScriptFile('jquery', $assets.'/library/jquery/jquery-1.11.1.js');
        $view->addScriptFile('jquery.mobile', $assets.'/library/jquery.mobile/jquery.mobile-1.4.5.js');
    }
}