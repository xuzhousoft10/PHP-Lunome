<?php
namespace X\Module\Administration\Action\Module;

/**
 * 
 */
use X\Core\X;
use X\Module\Administration\Util\Action;

/**
 * 
 */
class Index extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\Administration\Util\Action::run()
     */
    public function run($parameters=array()) {
        $modules = scandir(X::system()->getPath('Module'));
        foreach ( $modules as $index => $module ) {
            if ( '.' === $module[0] || X::system()->getModuleManager()->has($module) ) {
                continue;
            }
            X::system()->getModuleManager()->register($module);
        }
        $modules = X::system()->getModuleManager()->getList();
        
        $list = array();
        foreach ( $modules as $moduleName ) {
            $list[] = X::system()->getModuleManager()->get($moduleName);
        }
        
        $viewName = 'MODULE_INDEX';
        $viewPath = $this->getParticlePath('Module/Index');
        $viewData = array('modules'=>$list);
        $this->loadParticle('MODULE-DE', $viewPath, $viewData);
        
        $this->activeMenuItem('module');
        $this->title = '模块列表';
        $this->addBreadcrumbItem('模块列表', '#');
    }
}