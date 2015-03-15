<?php
namespace X\Util\Module;
/**
 * 
 */
use X\Core\X;
use X\Core\Module\XModule;
use X\Service\XAction\Service as XActionService;
/**
 * 
 */
abstract class Basic extends XModule {
    /**
     * @var \X\Service\XAction\Service
     */
    protected $actionService = null;
    
    /**
     * @var array
     */
    protected $parameters = array();
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::run()
     */
    public function run($parameters = array()) {
        $this->actionService = X::system()->getServiceManager()->get(XActionService::getServiceName());
        
        $namespace = get_class($this);
        $namespace = explode('\\', $namespace);
        array_pop($namespace);
        $namespace = implode('\\', $namespace);
        $group = $this->getName();
        $this->actionService->addGroup($group, $namespace);
        $this->actionService->getParameter()->merge($parameters);
        
        $defaultActionName= $this->getDefaultActionName();
        if ( null !== $defaultActionName ) {
            $this->actionService->setGroupDefaultAction($group, $defaultActionName);
            return $this->actionService->runGroup($this->getName());
        } else {
            return null;
        }
    }
    
    /**
     * @return \X\Service\XAction\Service
     */
    public function getActionService() {
        return $this->actionService;
    }
    
    /**
     * @return string
     */
    protected function getDefaultActionName() {
        return null;
    }
}