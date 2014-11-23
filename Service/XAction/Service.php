<?php
/**
 * Requirements
 */
namespace X\Service\XAction;

/**
 * Use statements
 */
use X\Core\X;
use X\Core\Util\KeyValue;
use X\Service\XAction\Core\Exception;
use X\Service\XAction\Core\Action\Create;

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
        $group['registered_actions'] = array();
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
        if ( !class_exists($actionClass) ) {
            throw new Exception("Can not find Action \"$action\" in group \"$group\".");
        }
        $action = new $actionClass($group);
        return $action;
    }
    
    /**
     * @param string $module
     * @param string $type
     * @param string $name
     */
    public function create($module, $type, $name) {
        $handler = new Create();
        $handler->run($module, $type, $name);
    }
    
    /**
     * @param unknown $module
     * @param unknown $name
     * @throws Exception
     */
    public function delete($module, $name) {
        $module = X::system()->getModuleManager()->get($module);
        $path = implode('/', array_map('ucfirst', explode('/', $name)));
        $path = $module->getPath('Action/'.$path.'.php');
        if ( !file_exists($path) ) {
            throw new Exception("Action \"$name\" does not exists.");
        }
        unlink($path);
        if ( 2 == count(scandir(dirname($path))) ) {
            rmdir(dirname($path));
        }
    }
    
    /**
     * @param string $group
     * @param string $action
     * @param string $handler
     */
    public function register( $group, $action, $handler ) {
        if ( !isset($this->groups[$group]) ) {
            throw new Exception("Group \"$group\" does not exists.");
        }
        
        if ( isset($this->groups[$group]['registered_actions'][$action]) ) {
            throw new Exception("Action \"$action\" already exists.");
        }
        
        $this->groups[$group]['registered_actions'][$action] = $handler;
    }
}