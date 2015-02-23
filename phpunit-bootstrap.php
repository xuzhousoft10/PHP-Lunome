<?php
require 'Core/X.php';
class PHPUnitBootstrap {
    public static function start() {
        X\Core\X::start(dirname(__FILE__));
    }
}
PHPUnitBootstrap::start();