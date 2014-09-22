<?php
/**
 * This file use to do the creation.
 */
namespace X\Service\XAction\Controller;

/**
 * Use statements
 */
use X\Core\X;
use X\Service\XAction\Core\Action;

/**
 * The action class.
 * 
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Create {
    /**
     * Execute the creation.
     * 
     * @return void
     */
    public function run($name, $type, $module) {
        try {
            $module = X::system()->getModuleManager()->get($module);
        } catch ( \X\Core\Module\Exception $e ) {
            printf("Module '%s' can not be found.\n", $module);
            return;
        }
        
        /* create action folder. */
        $actionPath = $module->getModulePath('Action');
        if ( !is_dir($actionPath) ) {
            mkdir($actionPath);
        }
        
        /* create action file path and namespace. */
        $actionFilePath = explode('/', $name);
        $actionNamespace = 'X\\Module\\'.$module->getName().'\\Action';
        $actionClassName = ucfirst(array_pop($actionFilePath));
        while ( 0 < count($actionFilePath) ) {
            $pathItem = ucfirst(array_shift($actionFilePath));
            $actionPath .= DIRECTORY_SEPARATOR.$pathItem;
            $actionNamespace .= '\\'.$pathItem;
            if ( !is_dir($actionPath) ) {
                mkdir($actionPath);
            }
        }
        $actionFilePath = $actionPath.DIRECTORY_SEPARATOR.$actionClassName.'.php';
        
        /* generate the action handler parent class name. */ 
        $handlerClassName = sprintf('\\X\\Service\\XAction\\Core\\Handler\\%sAction', ucfirst($type));
        if ( !class_exists($handlerClassName) ) {
            printf("Action type '%s' is not supported.\n", $type);
            return;
        }
        
        /* generate action handler name */
        $actionHandlerName = Action::getHandlerName();
        
        /* generate the content of action file. */
        $content = array();
        $content[] = '<?php';
        $content[] = "/**\n * The action file for $name action.\n */";
        $content[] = sprintf('namespace %s;', $actionNamespace);
        $content[] = '';
        $content[] = "/**\n * The action class for $name action.\n * @author Unknown\n */";
        $content[] = sprintf('class %s extends %s { ', $actionClassName, $handlerClassName);
        $content[] = "    /** \n     * The action handle for index action.\n     * @return void\n     */ ";
        $content[] = sprintf('    public function %s( /* @TODO Add parameters here if you need. */ ) {', $actionHandlerName);
        $content[] = '        /* @TODO Input your own code here. */';
        $content[] = '    }';
        $content[] = '}';
        $content = implode("\n", $content);
        
        /* save the action file. */
        file_put_contents($actionFilePath, $content);
    }
}