<?php
namespace X\Module\Admin\Action;
use X\Module\Admin\Util\Console;
use X\Core\X;
/**
 * Commands:
 *  use service {$name}
 *  use module {$name}
 *
 * @author michael
 */
class Xuse extends \X\Service\XAction\Core\Action {
    public function runAction( Console $console, $parameters ) {
        /* @var $commander \X\Module\Admin\Util\Commander */
        $commander = X::system()->getModuleManager()->get('Admin')->getCommander();
        $parameters = explode(' ', $parameters);
        array_map('trim', $parameters);
        
        $useType = $parameters[0];
        $usingName = isset($parameters[1]) ? ucfirst($parameters[1]) : '';
        
        switch ( $useType ) {
        case 'service' :
            try {
                $using = X::system()->getServiceManager()->get($usingName);
            } catch ( \X\Core\Service\Exception $e ) {
                $console->printLine('Unknown service "%s"', $parameters[1]);
                return;
            }
            break;
        case 'module' :
            try {
                $using = X::system()->getModuleManager()->get($usingName);
            } catch ( \X\Core\Module\Exception $e ) {
                $console->printLine('Unknown module "%s"', $parameters[1]);
                return;
            }
            break;
        default : 
            $console->printLine('Unknown using type', $useType);
            return;
        }
        
        $commander->usingType = $useType;
        $commander->using = $usingName;
    }
}