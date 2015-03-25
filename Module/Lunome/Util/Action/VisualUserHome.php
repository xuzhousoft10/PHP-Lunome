<?php
/**
 * 
 */
namespace X\Module\Lunome\Util\Action;

/**
 * 
 */
use X\Core\X;
use X\Module\Account\Service\Account\Service as AccountService;
use X\Module\Account\Module as AccountModule;

/**
 * 
 */
class VisualUserHome extends Visual {
    /**
     * @var unknown
     */
    protected $homeUserAccountID = null;
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Visual::beforeRunAction()
     */
    protected function beforeRunAction() {
        parent::beforeRunAction();
        
        $view = $this->getView();
        
        $layout = $this->getLayoutViewPath('UserHome');
        $view->setLayout($layout);
        $view->getDataManager()->set('currentUser', $this->getCurrentUserData());
        $name   = 'USER_TOP_BOARD';
        $path   = $this->getParticleViewPath('Home/TopBoard', AccountModule::getModuleName());
        $data   = array('homeUser'=>array());
        $particleView = $view->getParticleViewManager()->load($name, $path);
        $particleView->getDataManager()->merge($data);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Visual::afterRunAction()
     */
    protected function afterRunAction() {
        /* @var $accountService AccountService */
        $accountService = $this->getService(AccountService::getServiceName());
        $account = $accountService->get($this->homeUserAccountID);
        $accountProfile = $account->getProfileManager();
        $this->setDataToParticle('USER_TOP_BOARD', 'homeUser', $account);
        $this->getView()->title = $accountProfile->get('nickname')."的主页";
        parent::afterRunAction();
    }
}