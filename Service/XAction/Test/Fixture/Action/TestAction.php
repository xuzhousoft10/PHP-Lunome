<?php
namespace X\Service\XAction\Test\Fixture\Action;

/**
 * 
 */
use X\Service\XAction\Core\Util\Action;

/**
 * 
 */
class TestAction extends Action {
    /**
     * @param unknown $parm1
     * @return unknown
     */
    public function runAction( $parm1, $parm2=null ) {
        return $parm1;
    }
}