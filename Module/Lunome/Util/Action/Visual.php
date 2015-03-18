<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action;

/**
 * 
 */
use X\Core\X;
use X\Service\XView\Service as XViewService;
use X\Module\Lunome\Module as LunomeModule;

/**
 * 
 */
abstract class Visual extends \X\Util\Action\Visual {
    /**
     * @return string
     */
    public function getLunomeParticleViewPath($view) {
        $lunomeModule = X::system()->getModuleManager()->get(LunomeModule::getModuleName());
        $view = 'View/Particle/'.$view.'.php';
        $view = $lunomeModule->getPath($view);
        return $view;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeRunAction()
     */
    protected function beforeRunAction() {
        parent::beforeRunAction();
        
        /* Setup 404 content */
        $this->E404Content = array($this, 'error404Handler');
        
        /* Load navigation bar */
        $path   = $this->getLunomeParticleViewPath('Util/Navigation');
        $view = $this->getView()->getParticleViewManager()->load('INDEX_NAV_BAR', $path);
        $view->getOptionManager()->set('zone', 'header');
        $view->getDataManager()->set('user', $this->getCurrentUserData());
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        parent::beforeDisplay();
        $assetsURL = $this->getAssetsURL();
        $linkManager = $this->getView()->getLinkManager();
        $linkManager->addCSS('bootstrap',       $assetsURL.'/library/bootstrap/css/bootstrap.min.css');
        $linkManager->addCSS('bootstrap-theme', $assetsURL.'/library/bootstrap/css/bootstrap-theme.min.css');
        $linkManager->addCSS('application',     $assetsURL.'/css/application.css');
        $linkManager->addCSS('bootstrap-ext',   $assetsURL.'/css/bootstrap-ext.css');
        
        $scriptManager = $this->getView()->getScriptManager();
        $scriptManager->addFile('jquery',           $assetsURL.'/library/jquery/jquery-1.11.1.min.js');
        $scriptManager->addFile('bootstrap',        $assetsURL.'/library/bootstrap/js/bootstrap.min.js');
        $scriptManager->addFile('jquery-waypoints', $assetsURL.'/library/jquery/plugin/waypoints.js');
        $scriptManager->addFile('application',      $assetsURL.'/js/application.js');
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::afterRunAction()
     */
    protected function afterRunAction() {
        /* Load footer view */
        $name   = 'FOOTER';
        $path   = $this->getLunomeParticleViewPath('Util/Footer');
        $particleView = $this->getView()->getParticleViewManager()->load($name, $path);
        $particleView->getOptionManager()->set('zone', 'footer');
        
        parent::afterRunAction();
    }
    
    /**
     * @return multitype:unknown NULL
     */
    protected function getCurrentUserData() {
        $currentAccount = $this->getCurrentAccount();
        $profile = $currentAccount->getProfileManager();
        
        $userData = array();
        $userData['isGuest'] = null===$currentAccount;
        if ( !$userData['isGuest'] ) {
            $userData['id']         = $profile->get('account_id');
            $userData['nickname']   = $profile->get('nickname');
            $userData['photo']      = $profile->get('photo');
            $userData['account']    = $profile->get('account_number');
            $userData['isEditor']   = false;
            $userData['isManager']  = false;
        }
        return $userData;
    }
    
    /**
     * 设置页面的标题属性。
     * @param string $title
     * @return void
     */
    public function setPageTitle( $title ) {
        $systemName = $this->getModule()->getConfiguration()->get('system_name');
        $this->getView()->title = $title.' | '.$systemName;
    }
    
    /**
     * 用于输出404错误页面。该方法由视图渲染类使用。
     * @see \X\Service\XAction\Core\Handler\WebAction::$E404Content
     */
    protected function error404Handler() {
        /* @var $viewService \X\Service\XView\XViewService */
        $viewService = X::system()->getServiceManager()->get(XViewService::getServiceName());
        
        /* @var $view \X\Service\XView\Core\Handler\Html */
        $view = $viewService->createHtml('ERROR_404');
        $view->getMetaManager()->setCharset('UTF-8');
        $view->setLayout($this->getLayoutViewPath('Blank'));
        $view->getParticleViewManager()->load('ERROR_404_CONTENT', $this->getParticleViewPath('Util/Error404'));
        $view->display();
    }
}