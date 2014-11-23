<?php
namespace X\Module\Admin\Action;
use X\Module\Admin\Util\Console;
use X\Core\X;

/**
 * Commands:
 *  Quit
 *
 * @author michael
 */
class Custom extends \X\Service\XAction\Core\Action {
    public function runAction( Console $console, $parameters ) {
        /* @var $commander \X\Module\Admin\Util\Commander */
        $commander = X::system()->getModuleManager()->get('Admin')->getCommander();
        $usingType = $commander->usingType;
        $usingName = $commander->using;
        
        switch ( $usingType ) {
        case 'service' : 
            try {
                $using = X::system()->getServiceManager()->get($usingName);
            } catch ( \X\Core\Service\Exception $e ) {
                $console->printLine('Command has not been supported.');
                return;
            }
            break;
        case 'module' :
            try {
                $using = X::system()->getModuleManager()->get($usingName);
            } catch ( \X\Core\Service\Exception $e ) {
                $console->printLine('Command has not been supported.');
                return;
            }
            break;
        default:
            $console->printLine('Command has not been supported.');
            return;
        }
        
        $parameters = str_getcsv($parameters, ' ');
        $action = array_shift($parameters);
        call_user_func_array(array($using, $action), $parameters);
    }
}