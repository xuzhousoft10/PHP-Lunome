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
class Detail extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\Administration\Util\Action::run()
     */
    public function run($parameters=array()){
        $module = X::system()->getModuleManager()->get($parameters['name']);
        
        $this->activeMenuItem('module');
        $this->addBreadcrumbItem('模块列表', '/?module=administration&action=module/index');
        $this->addBreadcrumbItem($module->getPrettyName(), '#');
    }
}