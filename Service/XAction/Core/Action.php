<?php
/**
 * Namespace definition
 */
namespace X\Service\XAction\Core;

/**
 * The action class for XAction service.
 * 
 * @author Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 * 
 * @abstract integer runAction()
 */
abstract class Action extends Basic {
    /**
     * The name of group that this action belongs.
     * 
     * @var string
     */
    protected $group = null;
    
    /**
     * Get the name of group of action.
     * 
     * @return string
     */
    public function getGroup() {
        return $this->group;
    }
    
    /**
     * Initiate the action class.
     * 
     * @param string $group The name of group that this action belongs.
     */
    public function __construct( $group ) {
        $this->group = $group;
    }
    
    /**
     * The default action handler name
     * 
     * @var string
     */
    const ACTION_HANDLER_NAME = 'runAction';
    
    /**
     * 
     * @return string
     */
    public static function getHandlerName() {
        return self::ACTION_HANDLER_NAME;
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
        $this->beforeRunAction();
        $this->doRunAction($parameters);
        $this->afterRunAction();
    }
    
    /**
     * Execute the implement Action.
     * 
     * @return boolean|mixed
     */
    protected function doRunAction($parameters) {
        $handlerName = self::getHandlerName();
        if ( !method_exists($this, $handlerName) ) {
            throw new Exception(sprintf('Can not find action handler "%s".', $handlerName));
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
            } else if ( $parmInfo->allowsNull() ) {
                $paramsToMethod[$name] = null;
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