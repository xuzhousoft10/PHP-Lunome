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
        call_user_func_array(array($this, $actionHandler), $parameters);
    }
    
    /**
     * Action to list all modules
     * 
     * @param Console $console
     */
    protected function actionList( Console $console ) {
        $moduleManager = X::system()->getModuleManager();
        $modules = $moduleManager->getList();
        $console->printLine('| Name                 | Enable | Default |');
        foreach ( $modules as $moduleName ) {
            $stausMark = $moduleManager->isEnable($moduleName) ? 'O' : 'X';
            $defaultMark = $moduleManager->isDefault($moduleName) ? 'O' : 'X';
            $message = '| %s |   %s    |    %s    |';
            $console->printLine($message, str_pad($moduleName, 20, ' '), $stausMark, $defaultMark);
        }
    }
    
    /**
     * Enable the module by given name.
     * 
     * @param Console $console
     * @param string $name The name of module to enable.
     */
    protected function actionEnable( Console $console, $name ) {
        try {
            X::system()->getModuleManager()->enable($name);
        } catch ( Exception $e ) {
            $console->printLine('Unable to enable this module.');
        }
    }
    
    /**
     * Enable the module by given name.
     *
     * @param Console $console
     * @param string $name The name of module to disable.
     */
    protected function actionDisable( Console $console, $name ) {
        if ( 'ADMIN' === strtoupper($name) ) {
            $console->printLine('You can not disable admin module.');
            return;
        }
        
        try {
            X::system()->getModuleManager()->disable($name);
        } catch ( Exception $e ) {
            $console->printLine('Unable to disable this module.');
        }
    }
    
    /**
     * Set default module by given name.
     * 
     * @param Console $console
     * @param string $name
     */
    protected function actionDefault(  Console $console, $name  ) {
        if ( 'ADMIN' === strtoupper($name) ) {
            $console->printLine('You can not set admin module as default module.');
            return;
        }
        
        try {
            X::system()->getModuleManager()->setDefault('none'===$name ? null : $name);
        } catch ( Exception $e ) {
            $console->printLine('Unable to set this module as default.');
        }
    }
    
    /**
     * Create a new module by given name.
     * 
     * @param Console $console
     * @param unknown $name
     */
    protected function actionCreate( Console $console, $name ) {
        $name = trim($name);
        try {
            X::system()->getModuleManager()->create($name);
        } catch ( Exception $e ) {
            $console->printLine($e->getMessage());
        }
    }
    
    /**
     * Delete a module by given name.
     * @param Console $console
     * @param unknown $name
     */
    protected function actionDelete( Console $console, $name ) {
        $name = trim($name);
        try {
            X::system()->getModuleManager()->delete($name);
        } catch ( Exception $e ) {
            $console->printLine($e->getMessage());
        }
    }
    
    /**
     * 
     * @param Console $console
     * @param unknown $name
     */
    protected function actionMigrateCreate( Console $console, $module, $name ) {
        $name = trim($name);
        try {
            X::system()->getModuleManager()->migrateCreate($module, $name);
        } catch ( Exception $e ) {
            $console->printLine($e->getMessage());
        }
    }
    
    /**
     * Upgrade the module 
     * @return void
     */
    protected function actionMigrateUp( Console $console, $name ) {
        $name = trim($name);
        try {
            X::system()->getModuleManager()->migrateUp($name);
        } catch ( Exception $e ) {
            $console->printLine($e->getMessage());
        }
    }
}