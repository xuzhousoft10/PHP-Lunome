<?php
/**
 * Namespace defination
 */
namespace X\Core\Service;

use X\Core\X;
/**
 * The setting controller class for XService classes.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @since   Version 0.0.0
 * @version 0.0.0
 */
class SettingController extends \stdClass implements InterfaceSettingController {
    /**
     * The service instance.
     * 
     * @var \X\Core\Service\XService
     */
    protected $service = null;
    
    /**
     * Init the setting controller for current service.
     * 
     * @param \X\Core\Service\XService $service The service that this controller belongs.
     * @return void
     */
    public function __construct( $service ) {
        if ( !($service instanceof \X\Core\Service\XService) ) {
            throw new Exception(sprintf('The given $service is not a valid service instance.'));
        }
        
        $this->service = $service;
    }
    
    /**
     * Check whether the action exists by given name.
     * 
     * @param string $name The name of action to check.
     * @return boolean
     */
    public function hasAction( $name ) {
        return method_exists($this, $name);
    }
    
    /**
     * Execute the action of the controller
     * 
     * @param string $name The name of the action.
     * @param array $parameters The parameters to the action.
     * @return mixed
     */
    public function runAction( $name, $parameters ) {
        if ( !$this->hasAction($name) ) {
            throw new Exception(sprintf(''));
        }
        $action = $this->getMethodNameOfAction($name);
        $actionInfo = new \ReflectionMethod($this, $action);
        $parametersToHandler = array();
        foreach ( $actionInfo->getParameters() as $parmInfo ) {
            /* @var $parmInfo \ReflectionParameter */
            $parmName = $parmInfo->getName();
            if ( isset($parameters[$parmName]) ) {
                $parametersToHandler[$parmName] = $parameters[$parmName];
            } else if ( $parmInfo->isOptional() && $parmInfo->isDefaultValueAvailable() ) {
                $parametersToHandler[$parmName] = $parmInfo->getDefaultValue();
            } else if ( $parmInfo->allowsNull() ) {
                $parametersToHandler[$parmName] = null;
            } else {
                throw new Exception(sprintf('Invalid parameter "$%s" to "%s".', $parmName, $action));
            }
        }
        return call_user_func_array(array($this, $action), $parametersToHandler);
    }
    
    /**
     * Get the action name list of current controller.
     * 
     * @return string[]
     */
    public function getActions() {
        $controllerInfo = new \ReflectionClass($this);
        $actions = array();
        foreach ( $controllerInfo->getMethods(\ReflectionMethod::IS_PUBLIC) as $method ) {
            /* @var $method \ReflectionMethod */
            $methodName = $method->getName();
            if ( $this->isValidActionName($methodName) ) {
                $actions[] = $methodName;
            }
        }
        return $actions;
    }
    
    /**
     * Whether the give name is a valid action name.
     * 
     * @param string $methodName The method name to check.
     * @return boolean
     */
    protected function isValidActionName( $methodName ) {
        $isValid = strlen($methodName) > 6;
        $isValid = $isValid && ('action' == substr($methodName, 0, 6));
        return $isValid;
    }
    
    /**
     * 
     */
    public function actionHelp() {
        $serviceName = $this->service->getName();
        $docPath = X::system()->getPath(sprintf('Service/%s/Document/SettingHelp.txt', $serviceName));
        if ( file_exists($docPath) ) {
            echo file_get_contents($docPath);
            echo "\n";
        } else {
            echo "No document information for this service.";
        }
    }
}
