<?php
namespace X\Module\Admin\Action;
use X\Core\X;
use X\Core\Service\Exception;
use X\Module\Admin\Util\Console;
/**
 * 
 * Commands:
 *  module list
 *  module create {$name}
 *  
 * @author michael
 *
 */
class Service extends \X\Service\XAction\Core\Action {
    public function runAction( Console $console, $parameters ) {
        $parameters = explode(' ', $parameters);
        $actionHandler = 'action';
        while ( 0 < count($parameters) ) {
            $actionHandler .= ucfirst(array_shift($parameters));
            if ( method_exists($this, $actionHandler) ) {
                break;
            }
        }
        if ( !method_exists($this, $actionHandler) ) {
            $console->printLine('Command has not been supported.');
            return;
        }
        
        array_unshift($parameters, $console);
        
        $method = new \ReflectionMethod(__CLASS__, $actionHandler);
        $numberOfParameters = $method->getNumberOfParameters();
        call_user_func_array(array($this, $actionHandler), $parameters);
    }
    
    protected function actionList( Console $console ) {
        $services = X::system()->getServiceManager()->getList();
        foreach ( $services as $serviceName ) {
            $console->printLine($serviceName);
        }
    }
    
    protected function actionCreate( Console $console, $name, $module=null) {
        try {
            X::system()->getServiceManager()->create($name, $module);
        } catch ( Exception $e ) {
            $console->printLine($e->getMessage());
        }
    }
    
    protected function actionDelete( Console $console, $name ) {
        try {
            X::system()->getServiceManager()->delete($name);
        } catch ( Exception $e ) {
            $console->printLine($e->getMessage());
        }
    }
}