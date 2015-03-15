<?php
namespace X\Module\Account;
/**
 *
 */
use X\Util\Module\Basic;
/**
 * 
 */
class Module extends Basic {
    /**
     * @return string
     */
    protected function getDefaultActionName() {
        return 'login/index';
    }
}