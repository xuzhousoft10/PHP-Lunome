<?php
/**
 * The Lunome module
 */
namespace X\Module\Lunome;

/**
 * Use statements
 */
use X\Core\X;
use X\Service\XAction\Service as XActionService;

/**
 * The module class
 * 
 * @author Michael Luthor <michaelluthor@163.com>
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
        $actionService->add($group, __NAMESPACE__);
        $actionService->setParameters($parameters);
        $actionService->setDefaultAction($group, 'movie/index');
        $actionService->run($group);
    }
}