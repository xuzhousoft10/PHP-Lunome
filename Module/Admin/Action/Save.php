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
class Save extends \X\Service\XAction\Core\Action {
    public function runAction( Console $console, $parameters ) {
        /* @var $commander \X\Module\Admin\Util\Commander */
        $commander = X::system()->getModuleManager()->get('Admin')->getCommander();
        $usingType = $commander->usingType;
        $usingName = $commander->using;
        
        switch ( $usingType ) {
        case 'service' :
            try {
                /* @var $using \X\Core\Service\XService */
                $using = X::system()->getServiceManager()->get($usingName);
            } catch ( \X\Core\Service\Exception $e ) {
                $console->printLine('Command has not been supported.');
                return;
            }
            try {
                $using->getConfiguration()->save();
            } catch ( \X\Core\Exception $e ) {
                $console->printLine($e->getMessage());
            } catch ( \X\Core\Service\Exception $e ) {
                $console->printLine($e->getMessage());
            }
            break;
        case 'module' :
            $console->printLine('Unable to execute set command on modules.');
            try {
                $using->saveConfiguration();
            } catch ( \X\Core\Exception $e ) {
                $console->printLine($e->getMessage());
            } catch ( \X\Core\Service\Exception $e ) {
                $console->printLine($e->getMessage());
            }
            return;
        default :
            $using = X::system();
            try {
                $using->saveConfiguration();
            } catch ( \X\Core\Exception $e ) {
                $console->printLine($e->getMessage());
            } catch ( \X\Core\Service\Exception $e ) {
                $console->printLine($e->getMessage());
            }
            break;
        }
    }
}