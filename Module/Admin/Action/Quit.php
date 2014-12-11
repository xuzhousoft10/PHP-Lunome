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
class Quit extends \X\Service\XAction\Core\Action {
    public function runAction( Console $console, $parameters ) {
        X::system()->stop();
    }
}