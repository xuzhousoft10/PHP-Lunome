<?php
/**
 * 
 */
namespace X\Module\Backend;

/**
 * 
 */
use X\Core\X;
use X\Service\XAction\Service as XActionService;

/**
 * 
 */
class Module extends \X\Core\Module\XModule {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::run()
     */
    public function run($parameters = array()) {
        /* @var $actionService \X\Service\XAction\XActionService */
        $actionService = X::system()->getServiceManager()->get(XActionService::getServiceName());
        
        $group = $this->getName();
        $actionService->addGroup($group, __NAMESPACE__);
        $actionService->setGroupDefaultAction($group, 'account/index');
        $actionService->getParameter()->setValues($parameters);
        $actionService->runGroup($group);
    }
}