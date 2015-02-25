<?php
/**
 * Requirements
 */
namespace X\Service\XAction;

/**
 * Use statements
 */
use X\Core\X;
use X\Service\XAction\Core\Exception;
use X\Core\Util\ConfigurationArray;

/**
 * XActionService use to handle the Action request and 
 * execute the Action.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class Service extends \X\Core\Service\XService {
    /**
     * @var string
     */
    protected static $serviceName = 'XAction';
    
    /**
     * @var ConfigurationArray
     */
    private $parameters = null;
    
    /**
     * @var array
     */
    private $groups = array();
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::start()
     */
    public function start() {
        parent::start();
        $this->parameters = new ConfigurationArray();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::stop()
     */
    public function stop() {
        $this->parameters = null;
        parent::stop();
    }
    
    /**
     * @param string $name
     * @param string $namespace
     * @throws Exception
     */
    public function addGroup( $name, $namespace ) {
        if ( $this->hasGroup($name) ) {
            throw new Exception('Action group "'.$name.'" already exists.');
        }
        
        $group = array();
        $group['namespace']     = $namespace;
        $group['default']       = null;
        $group['running']       = null;
        $group['registered_actions'] = array();
        $this->groups[$name]    = $group;
    }
    
    /**
     * @param string $group
     * @param string $action
     * @param string $handler
     */
    public function register( $group, $action, $handler ) {
        if ( !$this->hasGroup($group) ) {
            throw new Exception('Action group "'.$group.'" does not exists.');
        }
        $this->groups[$group]['registered_actions'][$action] = $handler;
    }
    
    /**
     * @param string $name
     * @return boolean
     */
    public function hasGroup( $name ) {
        return isset($this->groups[$name]);
    }
    
    /**
     * @param string $groupName
     * @param string $action
     * @throws Exception
     */
    public function setGroupDefaultAction( $groupName, $action ){
        if ( !$this->hasGroup($groupName) ) {
            throw new Exception('Action group "'.$groupName.'" does not exists.');
        }
        $this->groups[$groupName]['default'] = $action;
    }
    
    /**
     * @return ConfigurationArray
     */
    public function getParameter() {
        return $this->parameters;
    }
    
    /**
     * @param string $name
     * @throws Exception
     */
    public function runGroup($name){
        if ( !$this->hasGroup($name) ) {
            throw new Exception('Action group "'.$name.'" does not exists.');
        }
        
        $actionName = $this->groups[$name]['default'];
        $actionName = $this->getParameter()->get('action', $actionName);
        if ( empty($actionName) ) {
            throw new Exception('Can not find available action in group "'.$name.'".');
        }
        
        return $this->runAction($name, $actionName);
    }
    
    /**
     * @param string $group
     * @param string $action
     */
    public function runAction($group, $action) {
        $action = $this->getActionByName($group, $action);
        $this->groups[$group]['running'] = $action;
        $parameters = $this->getParameter()->toArray();
        unset($parameters['action']);
        return $action->run($parameters);
    }
    
    /**
     * @param string $group
     * @param string $action
     * @throws Exception
     * @return \X\Service\XAction\Core\Action
     */
    private function getActionByName( $group, $action ) {
        if ( isset($this->groups[$group]['registered_actions'][$action]) ) {
            $handler = $this->groups[$group]['registered_actions'][$action];
            if ( class_exists($handler) ) {
                $handler = new $handler($group);
                return $handler;
            }
        }
        
        $actionClass = explode('/', $action);
        $actionClass = array_map('ucfirst', $actionClass);
        $actionClass = implode('\\', $actionClass);
        $namespace = $this->groups[$group]['namespace'].'\\Action';
        $actionClass = $namespace.'\\'.$actionClass;
        if ( class_exists($actionClass) ) {
            $action = new $actionClass($group);
            return $action;
        }
        
        throw new Exception('Can not find Action "'.$action.'" in group "'.$group.'".');
    }
}