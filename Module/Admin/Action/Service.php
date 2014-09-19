<?php
namespace X\Module\Admin\Action;
use X\Core\X;
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
        $services = X::system()->getServiceManager()->getList();
        foreach ( $services as $serviceName ) {
            $console->printLine($serviceName);
        }
    }
}