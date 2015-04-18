<?php
/**
 * Namespace definition
 */
namespace X\Service\XAction\Core\Util;

/**
 * 
 */
use X\Service\XAction\Core\Exception;

/**
 * The action class for XAction service.
 * @author Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 * @method mixed runAction()
 */
abstract class Action {
    /**
     * The name of group that this action belongs.
     * @var string
     */
    protected $groupName = null;
    
    /**
     * Get the name of group of action.
     * @return string
     */
    public function getGroupName() {
        return $this->groupName;
    }
    
    /**
     * Initiate the action class.
     * 
     * @param string $group The name of group that this action belongs.
     */
    public function __construct( $groupName ) {
        $this->groupName = $groupName;
    }
    
    private $parameters = array();
    
    /**
     * @param unknown $name
     * @return Ambigous <NULL, multitype:>
     */
    public function getParameter( $name ) {
        return key_exists($name, $this->parameters) ? $this->parameters[$name] : null;
    }
    
    /**
     * Execute this Action. If beforeRunAction returns false, then
     * it would not execute the Action and return false as Action 
     * result. Also, if Action implement method returns false, then
     * afterRunAction method would not be executed.
     * Notice, You should overwrite 'runAction' method to implment
     * the processing of Action. If there is not runAction method,
     * it would not give you any error message, but it would still
     * execute the afterRunAction method.
     * Also, you can redifine the ACTION_HANDLER_NAME to rename the
     * Action name of implemention.
     * 
     * @return boolean|unknown
     */
    public function run( $parameters=array() ){
        $this->parameters = $parameters;
        $this->beforeRunAction();
        $result = $this->doRunAction($parameters);
        $this->afterRunAction();
        return $result;
    }
    
    /**
     * Execute the implement Action.
     * 
     * @return boolean|mixed
     */
    protected function doRunAction($parameters) {
        $handlerName = 'runAction';
        if ( !method_exists($this, $handlerName) ) {
            throw new Exception("Can not find action handler \"runAction()\".");
        }
        
        $paramsToMethod = array();
        $class = new \ReflectionClass($this);
        $method = $class->getMethod($handlerName);
        
        $parameterInfos = $method->getParameters();
        foreach ( $parameterInfos as $parmInfo ) {
            /* @var $parmInfo \ReflectionParameter */
            $name = $parmInfo->getName();
            if ( isset($parameters[$name]) ) {
                $paramsToMethod[$name] = $parameters[$name];
            } else if ( $parmInfo->isOptional() && $parmInfo->isDefaultValueAvailable() ) {
                $paramsToMethod[$name] = $parmInfo->getDefaultValue();
            } else {
                throw new Exception('Parameters to action handler is not available.');
            }
        }
        
        $handler = array($this, $handlerName);
        return \call_user_func_array($handler, $paramsToMethod);
    }
    
    /**
     * The method that would be called before the action executed.
     * 
     * @return void
     */
    protected function beforeRunAction(){}
    
    /**
     * The method would be called after the action executed.
     * 
     * @return void
     */
    protected function afterRunAction(){}
}