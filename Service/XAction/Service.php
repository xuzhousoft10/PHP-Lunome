<?php
/**
 * Requirements
 */
namespace X\Service\XAction;

/**
 * Use statements
 */
use X\Service\XAction\Core\Exception;

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
     * This value holds the service instace.
     *
     * @var XService
     */
    protected static $service = null;
    
    /**
     * This value contains all the group definistions.
     * 
     * @var array
     */
    protected $groups = array(
    /* 'name' => array(
     *      'namespace'     => 'namespace',
     *      'default'       => 'action',
     *      'running'       => null,
     * ), 
     */
    );
    
    /**
     * Add group to action service.
     * 
     * @param string $group The name of group
     * @param string $namespace The name of namespace for the group. 
     *                          you can easilly use __NAMESPACE__
     *                          for this parameter.
     */
    public function add( $group, $namespace ){
        $this->groups[$group] = array();
        $this->groups[$group]['namespace'] = $namespace;
        $this->groups[$group]['default'] = null;
        $this->groups[$group]['running'] = null;
    }
    
    /**
     * Set the default Action for group
     * 
     * @param string $group
     * @param string $action
     * @return XActionService
     */
    public function setDefaultAction( $group, $action ){
        if ( !isset($this->groups[$group]) ) {
            throw new Exception(sprintf('Action group "%s" has not been added.', $group));
        }
        $this->groups[$group]['default'] = $action;
    }
    
    /**
     * This value contains the parameters to actions.
     *
     * @var array
     */
    protected $parameters = array();
    
    /**
     * Add or modify the parameters in management.
     *
     * @param string $name
     * @param mixed $value
    */
    public function setParameter($name, $value){
        $this->parameters[$name] = $value;
    }
    
    /**
     * Get parameter from management
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getParameter($name, $default=null){
        return isset($this->parameters[$name]) ? $this->parameters[$name] : $default;
    }
    
    /**
     * Get all available parameters.
     *
     * @return array
     */
    public function getParameters() {
        return $this->parameters;
    }
    
    /**
     * Remove parameter from parameter array.
     *
     * @param string $name
     */
    public function removeParameter( $name ){
        if ( isset($this->parameters[$name]) ) {
            unset($this->parameters[$name]);
        }
    }
    
    /**
     * Set parameters to Action
     *
     * @param array $parameters
     */
    public function setParameters( $parameters ){
        $this->parameters = array_merge($this->parameters, $parameters);
    }
    
    /**
     * Run Action group
     * 
     * @param string $group
     * @return boolean
     */
    public function run($group){
        if ( !isset($this->groups[$group]) ) {
            throw new Exception(sprintf('Action group "%s" has not been added.', $group));
        }
        
        $actionName = $this->getParameter('action', $this->groups[$group]['default']);
        if ( is_null($actionName) ) {
            throw new Exception(sprintf('Not action available in group "%s".', $group));
        }
        
        $this->runAction($group, $actionName);
    }
    
    /**
     * The name of group that current Action belongs.
     * 
     * @var string
     */
    protected $runningGroup = null;

    /**
     * Get the name of group that current Action belongs.
     * 
     * @return string
     */
    public function getRunningGroup() {
        return $this->runningGroup;
    }
    
    /**
     * The name of current Action.
     * 
     * @var string
     */
    protected $runningAction = null;
    
    /**
     * Get the name of current Action.
     * 
     * @return string
     */
    public function getRunningAction() {
        return $this->runningAction;
    }
    
    /**
     * Execute the Action by given group and name.
     * 
     * @param string $group The name of group that Action belongs
     * @param string $name The name of Action to execute
     * @throws Exception
     */
    public function runAction($group, $name) {
        $action = $this->getActionByName($group, $name);
        $this->runningGroup = $group;
        $this->runningAction = $name;
        $this->groups[$group]['running'] = $action;
        $parameters = $this->getParameters();
        unset($parameters['action']);
        $action->run($parameters);
    }
    
    /**
     * Get Action instance by given group name and Action name.
     * 
     * @param string $group The name of group.
     * @param string $Action The name of Action.
     * @return \X\Service\XAction\Core\Action
     */
    protected function getActionByName( $group, $action ) {
        $actionClass = explode('/', $action);
        $actionClass = array_map('ucfirst', $actionClass);
        $actionClass = implode('\\', $actionClass);
        $namespace = $this->groups[$group]['namespace'].'\\Action';
        $actionClass = $namespace.'\\'.$actionClass;
        if ( !class_exists($actionClass) ) {
            throw new Exception(sprintf('Can not find Action "%s" in group "%s".', $action, $group));
        }
        
        $action = new $actionClass($group);
        return $action;
    }
    
    /**
     * This value contains current Action.
     *
     * @var Action
     */
    protected $action = null;
    
    /**
     * Get the instance of current Action.
     * 
     * @return \X\Service\XAction\Action
     */
    public function getCurrentAction(){
        return $this->action;
    }
}

return  __NAMESPACE__;