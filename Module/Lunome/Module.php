<?php
namespace X\Module\Lunome;
class Module extends \X\Core\Module\XModule {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::run()
     */
    public function run($parameters = array()) {
        echo "This is a new module.";
    }
}