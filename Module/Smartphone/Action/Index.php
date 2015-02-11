<?php
/**
 * 
 */
namespace X\Module\Smartphone\Action;

/**
 * 
 */
use X\Module\Smartphone\Util\Action\Visual;

/**
 * 
 */
class Index extends Visual {
    /**
     * 
     */
    public function runAction() {
        $view = $this->getView();
        
        $viewName = 'SMARTPHONE_INDEX';
        $viewPath = $this->getParticleViewPath('Index');
        $view->loadParticle($viewName, $viewPath);
        $view->title = '智能手机测试标题';
    }
}