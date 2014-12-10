<?php
namespace X\Module\Admin\Action;
use X\Module\Admin\Util\Console;
use X\Core\X;
/**
 * Commands:
 *  set value
 *
 * @author michael
 */
class Set extends \X\Service\XAction\Core\Action {
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
            $seperatorPos = strpos($parameters, '=');
            $name = trim(substr($parameters, 0, $seperatorPos));
            $value = trim(substr($parameters, $seperatorPos+1));
            $using->getConfiguration()->set($name, $value);
            break;
        case 'module' :
            $console->printLine('Unable to execute set command on modules.');
            $seperatorPos = strpos($parameters, '=');
            $name = trim(substr($parameters, 0, $seperatorPos));
            $value = trim(substr($parameters, $seperatorPos+1));
            $using->setConfiguration($name, $value);
            return;
        default :
            $using = X::system();
            $seperatorPos = strpos($parameters, '=');
            $name = trim(substr($parameters, 0, $seperatorPos));
            $value = trim(substr($parameters, $seperatorPos+1));
            $using->setConfiguration($name, $value);
            break;
        }
    }
}