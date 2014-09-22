<?php
/**
 * This file define a basic visual action class.
 */
namespace X\Util\Action;

/**
 * Use statements
 */
use X\Core\X;
use X\Service\XView\XViewService;

/**
 * The basic action class
 *
 * @author Michael Luthor <michaelluthor@163.com>
 */
abstract class Visual extends Basic {
    /**
     * This array contains all view stuffs.
     * 
     * @var array
     */
    private $view = array(
        'service' => null, /* The xview service. */
        'object'  => null, /* The view object. */
        'name'    => null, /* The name of the view.*/
    );
    
    /**
     * Get the view object of this action.
     * 
     * @return \X\Service\XView\Core\Handler\Html
     */
    public function getView() {
        return $this->view['object'];
    }
    
    /**
     * Get the view service.
     * 
     * @return \X\Service\XView\XViewService
     */
    public function getViewService() {
        return $this->view['service'];
    }
    
    /**
     * Get the name of the view.
     * 
     * @return string
     */
    public function getViewName() {
        return $this->view['name'];
    }
    
    /**
     * Get the particle view path by given name. For example,
     * If the $view is 'Particle/User/Login', can the module of this action is 
     * 'Test', then you can get view path like this :
     * '{$root_path}/Module/Test/View/Particle/User/Login.php'
     * 
     * @param string $view The view name
     * @return string
     */
    public function getParticleViewPath( $view ) {
        $view = sprintf('View/Particle/%s.php', $view);
        $view = $this->getModule()->getModulePath($view);
        return $view;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Action::beforeRunAction()
     */
    protected function beforeRunAction() {
        /* @var $viewService \X\Service\XView\XViewService */
        $viewService = X::system()->getServiceManager()->get(XViewService::getServiceName());
        $viewName = str_replace('\\', '_', get_class($this));
        $viewType = XViewService::VIEW_TYPE_HTML;
        
        $this->view['service'] = $viewService;
        $this->view['object'] = $viewService->create($viewName, $viewType);
        $this->view['name'] = $viewType;
        
        parent::beforeRunAction();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Action::afterRunAction()
     */
    protected function afterRunAction() {
        $this->getView()->addCssLink('bootstrap', 'Assets/library/bootstrap/css/bootstrap.css');
        $this->getView()->addCssLink('bootstrap-theme', 'Assets/library/bootstrap/css/bootstrap-theme.css');
        $this->getView()->addCssLink('application', 'Assets/css/application.css');
        $this->getView()->addCssLink('bootstrap-ext', 'Assets/css/bootstrap-ext.css');
        
        $this->getView()->addScriptFile('jquery', 'Assets/library/jquery/jquery-1.11.1.js');
        $this->getView()->addScriptFile('bootstrap', 'Assets/library/bootstrap/js/bootstrap.js');
        
        
        $this->getView()->display();
    }
}