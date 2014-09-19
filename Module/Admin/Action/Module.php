<?php
/**
 * The module management handler.
 */
namespace X\Module\Admin\Action;

/**
 * Use statements
 */
use X\Core\X;
use X\Core\Module\Exception;
use X\Module\Admin\Util\Console;

/**
 * The class to manage modules.
 * 
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Module extends \X\Service\XAction\Core\Action {
    /**
     * Handle the module command.
     * 
     * @param Console $console
     * @param string $parameters
     * @return void
     */
    public function runAction( Console $console, $parameters ) {
        $separatorPos = strpos($parameters, ' ');
        if ( false !== $separatorPos ) {
            $subAction = substr($parameters, 0, $separatorPos);
            $parameters = substr($parameters, $separatorPos+1);
        } else {
            $subAction = $parameters;
        }
        
        $actionHandler = sprintf('action%s', ucfirst($subAction));
        call_user_func_array(array($this, $actionHandler), array($console, $parameters));
    }
    
    /**
     * Action to list all modules
     * 
     * @param Console $console
     */
    protected function actionList( Console $console ) {
        $moduleManager = X::system()->getModuleManager();
        $modules = $moduleManager->getList();
        foreach ( $modules as $moduleName ) {
            $stausMark = $moduleManager->isEnable($moduleName) ? 'O' : 'X';
            $console->printLine('[%s] %s', $stausMark, $moduleName);
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