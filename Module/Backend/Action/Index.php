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
        $this->setPageTitle('首页');
        $this->setMenuItemActived(self::MENU_ITEM_DEFAULT);
    }
}