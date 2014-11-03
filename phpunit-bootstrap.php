<?php
require 'Core/X.php';
class PHPUnitBootstrap {
    public static function start() {
        X\Core\X::start(dirname(__FILE__));
        X\Core\X::system()->isTesting = true;
    }
}
PHPUnitBootstrap::start();