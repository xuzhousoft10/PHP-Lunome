<?php
namespace X\Module\Account;
/**
 * 
 */
use X\Core\Module\XModule;
/**
 * 
 */
class Module extends XModule {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::run()
     */
    public function run($parameters = array()) {
        echo 'Got Account Module.';
    }
}