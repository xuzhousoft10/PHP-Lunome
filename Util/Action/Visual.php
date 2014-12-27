<?php
/**
 * This file define a basic visual action class.
 */
namespace X\Util\Action;

/**
 * Use statements
 */
use X\Core\X;
use X\Service\XView\Service as XViewService;

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
        $view = $this->getModule()->getPath($view);
        return $view;
    }
    
    /**
     * 
     * @param unknown $layout
     * @return Ambigous <string, unknown>
     */
    public function getLayoutViewPath( $layout ) {
        $view = sprintf('View/Layout/%s.php', $layout);
        $view = $this->getModule()->getPath($view);
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
        
        $this->view['object']->setCharset('UTF-8');
        parent::beforeRunAction();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Action::afterRunAction()
     */
    protected function afterRunAction() {
        $assetsURL = X::system()->getConfiguration()->get('assets-base-url');
        $this->getView()->addCssLink('bootstrap',       $assetsURL.'/library/bootstrap/css/bootstrap.min.css');
        $this->getView()->addCssLink('bootstrap-theme', $assetsURL.'/library/bootstrap/css/bootstrap-theme.min.css');
        $this->getView()->addCssLink('application',     $assetsURL.'/css/application.css');
        $this->getView()->addCssLink('bootstrap-ext',   $assetsURL.'/css/bootstrap-ext.css');
        
        $this->getView()->addScriptFile('jquery',           $assetsURL.'/library/jquery/jquery-1.11.1.min.js');
        $this->getView()->addScriptFile('jquery-waypoints', $assetsURL.'/library/jquery/plugin/waypoints.js');
        $this->getView()->addScriptFile('bootstrap',        $assetsURL.'/library/bootstrap/js/bootstrap.min.js');
        $this->getView()->addScriptFile('application',      $assetsURL.'/js/application.js');
        
        /* 该行代码是为了完成新浪微博网站所有权的验证。 */
        $this->getView()->addOpenGraphData('SINA-WEIBO-VERIFICATION', 'wb:webmaster', '9598c04587327873');
        
        if ( $this->getView()->hasLayout() ) {
            $this->getView()->display();
        }
    }
}
