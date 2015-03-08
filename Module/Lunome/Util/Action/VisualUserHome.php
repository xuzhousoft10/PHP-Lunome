<?php
/**
 * 
 */
namespace X\Module\Lunome\Util\Action;

/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Service\Region\Service as RegionService;

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
        $path   = $this->getParticleViewPath('User/Home/TopBoard');
        $data   = array('homeUser'=>array());
        $particleView = $view->getParticleViewManager()->load($name, $path);
        $particleView->getDataManager()->merge($data);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Visual::afterRunAction()
     */
    protected function afterRunAction() {
        /* @var $regionService \X\Module\Lunome\Service\Region\Service */
        $regionService = X::system()->getServiceManager()->get(RegionService::getServiceName());
        $accountManager = $this->getUserService()->getAccount();
        $userData = $this->getUserService()->getAccount()->getInformation($this->homeUserAccountID)->toArray();
        $userData['living_country']     = $regionService->getNameByID($userData['living_country']);
        $userData['living_province']    = $regionService->getNameByID($userData['living_province']);
        $userData['living_city']        = $regionService->getNameByID($userData['living_city']);
        $userData['sexSign']            = $accountManager->getSexMark($userData['sex']);
        $userData['sex']                = $accountManager->getSexName($userData['sex']);
        $userData['sexuality']          = $accountManager->getSexualityName($userData['sexuality']);
        $userData['emotion_status']     = $accountManager->getEmotionStatuName($userData['emotion_status']);
        $this->setDataToParticle('USER_TOP_BOARD', 'homeUser', $userData);
        
        $this->getView()->title = $userData['nickname']."的主页";
        
        parent::afterRunAction();
    }
}