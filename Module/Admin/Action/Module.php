<?php
namespace X\Module\Admin\Action;
use X\Core\X;
use X\Core\Module\Exception;
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
class Module extends \X\Service\XAction\Core\Action {
    public function runAction( Console $console, $parameters ) {
        $separatorPos = strpos($parameters, ' ');
        if ( false !== $separatorPos ) {
            $subAction = substr($parameters, 0, $separatorPos);
            $parameters = substr($parameters, $separatorPos+1);
        } else {
            $subAction = $parameters;
        }
        
        $actionHandler = sprintf('action%s', ucfirst($subAction));
        return call_user_func_array(array($this, $actionHandler), array($console, $parameters));
    }
    
    protected function actionList( Console $console ) {
        $modules = X::system()->getModuleManager()->getList();
        foreach ( $modules as $moduleName ) {
            $console->printLine($moduleName);
        }
    }
    
    protected function actionCreate( Console $console, $name ) {
        $name = trim($name);
        try {
            X::system()->getModuleManager()->create($name);
        } catch ( Exception $e ) {
            $console->printLine($e->getMessage());
        }
    }
    
    protected function actionDelete( Console $console, $name ) {
        $name = trim($name);
        try {
            X::system()->getModuleManager()->delete($name);
        } catch ( Exception $e ) {
            $console->printLine($e->getMessage());
        }
    }
}