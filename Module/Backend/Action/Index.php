<?php
/**
 * 
 */
namespace X\Module\Backend\Action;

/**
 * 
 */
use X\Module\Backend\Util\Action\Visual;

/**
 * 
 */
class Index extends Visual {
    /**
     * 
     */
    public function runAction() {
        $view = $this->getView();
        
        $viewName = 'BACKEND_INDEX';
        $viewPath = $this->getParticleViewPath('Index');
        $view->loadParticle($viewName, $viewPath);
        
        $view->title = 'Lunome系统管理';
    }
}