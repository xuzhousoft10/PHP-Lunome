<?php
namespace X\Module\Administration;

/**
 *
 */
use X\Module\Administration\Util\Exception;
use X\Module\Administration\Util\Action;

/**
 * 
 */
class Module extends \X\Core\Module\XModule {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::run()
     */
    public function run($parameters = array()) {
        $action = isset($parameters['action']) ? $parameters['action'] : null;
        $action = null===$action ? $this->getConfiguration()->get('default_action') : $action;
        if ( null === $action ) {
            throw new Exception('Unable to find action to execute.');
        }
        
        $action = $this->getAction($action);
        $action->run($parameters);
        $action->display();
    }
    
    /**
     * @param string $action
     * @throws Exception
     * @return \X\Module\Administration\Util\Action
     */
    private function getAction( $action ) {
        $action = explode('/', $action);
        $action = array_map('ucfirst', $action);
        $action = implode('\\', $action);
        $actionClass = __NAMESPACE__.'\\Action\\'.$action;
        if ( !class_exists($actionClass) ) {
            throw new Exception('Action class "'.$actionClass.'" does not exists.');
        }
        
        $action = new $actionClass($this);
        if ( !($action instanceof Action) ) {
            throw new Exception('Action class "'.$actionClass.'" is not a validate action.');
        }
        return $action;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::getPrettyName()
     */
    public function getPrettyName() {
        return 'X-Framework管理';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::getDescription()
     */
    public function getDescription() {
        return '该模块用来在线管理X-Framework的各种服务与模块。实现开发级别的框架管理。';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::getVersion()
     */
    public function getVersion() {
        return array(1,0,0);
    }
}