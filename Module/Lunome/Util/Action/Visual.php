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
    protected function beforeRunAction() {
        parent::beforeRunAction();
        
        /* Setup 404 content */
        $this->E404Content = array($this, 'error404Handler');
        
        /* Load navigation bar */
        $name   = 'INDEX_NAV_BAR';
        $path   = $this->getParticleViewPath('Util/Navigation');
        $option = array('zone'=>'header');
        $data   = array('user'=>$this->getCurrentUserData());
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::afterRunAction()
     */
    protected function afterRunAction() {
        /* Load footer view */
        $name   = 'FOOTER';
        $path   = $this->getParticleViewPath('Util/Footer');
        $option = array('zone'=>'footer');
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        parent::afterRunAction();
    }
    
    /**
     * @return multitype:unknown NULL
     */
    protected function getCurrentUserData() {
        $userData = array();
        $userData['isGuest'] = $this->getUserService()->getIsGuest();
        if ( !$userData['isGuest'] ) {
            $data = $this->getUserService()->getCurrentUser();
            $userData['id']         = $data['ID'];
            $userData['nickname']   = $data['NICKNAME'];
            $userData['photo']      = $data['PHOTO'];
            $userData['account']    = $data['ACCOUNT'];
            $userData['isAdmin']    = $data['IS_ADMIN'];
        }
        return $userData;
    }
    
    /**
     * 
     */
    protected function error404Handler() {
        /* @var $viewService \X\Service\XView\XViewService */
        $viewService = X::system()->getServiceManager()->get(XViewService::getServiceName());
        $viewType = XViewService::VIEW_TYPE_HTML;
        
        /* @var $view \X\Service\XView\Core\Handler\Html */
        $view = $viewService->create('ERROR_404', $viewType);
        $view->setCharset('UTF-8');
        $view->loadLayout($this->getLayoutViewPath('Blank'));
        $view->loadParticle('ERROR_404_CONTENT', $this->getParticleViewPath('Util/Error404'));
        $view->display();
    }
}