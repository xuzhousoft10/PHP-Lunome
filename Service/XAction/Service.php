<?php
/**
 * Requirements
 */
namespace X\Service\XAction;

/**
 * Use statements
 */
use X\Service\XAction\Core\Exception;
use X\Core\Util\KeyValue;

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
     * @var array
     */
    private $groups = array();
    
    /**
     * @param string $name
     * @param string $namespace
     * @throws Exception
     */
    public function addGroup( $name, $namespace ) {
        if ( isset($this->groups[$name]) ) {
            throw new Exception("Action group \"$name\" already exists.");
        }
        
        $group = array();
        $group['namespace']     = $namespace;
        $group['default']       = null;
        $group['running']       = null;
        $this->groups[$name]    = $group;
    }
    
    /**
     * @param string $group
     * @param string $action
     * @throws Exception
     */
    public function setGroupDefaultAction( $name, $action ){
        if ( !isset($this->groups[$name]) ) {
            throw new Exception("Action group \"$name\" does not exists.");
        }
        $this->groups[$name]['default'] = $action;
    }
    
    /**
     * @var KeyValue
     */
    private $parameters = null;
    
    /**
     * @return \X\Core\Util\KeyValue
     */
    public function getParameter() {
        if ( null === $this->parameters ) {
            $this->parameters = new KeyValue();
        }
        return $this->parameters;
    }
    
    /**
     * @param string $name
     * @throws Exception
     */
    public function runGroup($name){
        if ( !isset($this->groups[$name]) ) {
            throw new Exception("Action group \"$name\" does not exists.");
        }
        
        $actionName = $this->groups[$name]['default'];
        $actionName = $this->getParameter()->get('action', $actionName);
        if ( empty($actionName) ) {
            throw new Exception("Can not find available action in group \"$name\".");
        }
        
        $this->runAction($name, $actionName);
    }
    
    /**
     * @param string $group
     * @param string $action
     */
    public function runAction($group, $action) {
        $action = $this->getActionByName($group, $action);
        $this->groups[$group]['running'] = $action;
        $parameters = $this->getParameter()->getValues();
        unset($parameters['action']);
        $action->run($parameters);
    }
    
    /**
     * @param string $group
     * @param string $action
     * @throws Exception
     * @return \X\Service\XAction\Core\Action
     */
    protected function getActionByName( $group, $action ) {
        $actionClass = explode('/', $action);
        $actionClass = array_map('ucfirst', $actionClass);
        $actionClass = implode('\\', $actionClass);
        $namespace = $this->groups[$group]['namespace'].'\\Action';
        $actionClass = $namespace.'\\'.$actionClass;
        if ( !class_exists($actionClass) ) {
            throw new Exception("Can not find Action \"$action\" in group \"$group\".");
        }
        $action = new $actionClass($group);
        return $action;
    }
}