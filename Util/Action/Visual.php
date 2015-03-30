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
use X\Module\Lunome\Module as LunomeModule;

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
    public function getParticleViewPath( $view, $module=null ) {
        if ( null === $module ) {
            $module = $this->getModule();
        } else {
            $module = X::system()->getModuleManager()->get($module);
        }
        $view = sprintf('View/Particle/%s.php', $view);
        $view = $module->getPath($view);
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
     * @param unknown $name
     * @param unknown $path
     * @param unknown $option
     * @param unknown $data
     * @return \X\Service\XView\Core\Util\HtmlView\ParticleView
     */
    public function loadParticle($name, $path, $option=array(), $data=array()) {
        $view = $this->getView()->getParticleViewManager()->load($name, $path);
        $view->getDataManager()->merge($data);
        $view->getOptionManager()->merge($option);
        return $view;
    }
    
    /**
     * @param unknown $particleName
     * @param unknown $name
     * @param unknown $value
     */
    public function setDataToParticle( $particleName, $name, $value ) {
        $view = $this->getView()->getParticleViewManager()->get($particleName);
        $view->getDataManager()->set($name, $value);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Action::beforeRunAction()
     */
    protected function beforeRunAction() {
        /* @var $viewService \X\Service\XView\XViewService */
        $viewService = X::system()->getServiceManager()->get(XViewService::getServiceName());
        $viewName = str_replace('\\', '_', get_class($this));
        
        $viewObject = $viewService->createHtml($viewName);
        $this->view['service'] = $viewService;
        $this->view['object'] = $viewObject;
        $this->view['name'] = $viewName;
        
        $viewObject->getMetaManager()->setCharset('UTF-8');
        $viewObject->getDataManager()->set('assetsURL', $this->getAssetsURL());
        $viewObject->setAssetsBaseURL($this->getAssetsURL());
        $basicAssetsLoader = $this->getParticleViewPath('Util/BasicAssetsLoader', LunomeModule::getModuleName());
        $viewObject->getParticleViewManager()->load('basic-assets-loader', $basicAssetsLoader);
        parent::beforeRunAction();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Action::afterRunAction()
     */
    protected function afterRunAction() {
        if ( false !== $this->beforeDisplay() ) {
            $this->getView()->display();
        }
    }
    
    /**
     * 该方法用于在执行显示页面之前调用，你可以重写该方法来进行页面显示前的操作。
     * 如果该方法返回false， 则不再进行页面显示的操作。
     * 如果该方法不返回值或者其他非false的值， 则将会执行页面显示的操作。
     * @return boolean
     */
    protected function beforeDisplay() {
        return true;
    }
}
