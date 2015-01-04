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
        $view->loadLayout($layout);
        $view->addData('currentUser', $this->getCurrentUserData());
        
        $name   = 'USER_TOP_BOARD';
        $path   = $this->getParticleViewPath('User/Home/TopBoard');
        $option = array();
        $data   = array('homeUser'=>array());
        $view->loadParticle($name, $path, $option, $data);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Visual::afterRunAction()
     */
    protected function afterRunAction() {
        /* @var $regionService \X\Module\Lunome\Service\Region\Service */
        $regionService = X::system()->getServiceManager()->get(RegionService::getServiceName());
        $userData = $this->getUserService()->getAccount()->getInformation($this->homeUserAccountID);
        $userData->living_country = $regionService->getNameByID($userData->living_country);
        $userData->living_province = $regionService->getNameByID($userData->living_province);
        $userData->living_city = $regionService->getNameByID($userData->living_city);
        $this->getView()->setDataToParticle('USER_TOP_BOARD', 'homeUser', $userData);
        
        $this->getView()->title = $userData->nickname."的主页";
        
        parent::afterRunAction();
    }
}