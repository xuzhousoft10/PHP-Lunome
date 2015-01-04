<?php
/**
 * 
 */
namespace X\Module\Lunome\Action\User\Home;

/**
 *
 */
use X\Module\Lunome\Util\Action\VisualUserHome;

/**
 * 
 */
class Index extends VisualUserHome {
    /**
     * @param unknown $id
     */
    public function runAction( $id ) {
        $this->homeUserAccountID = $id;
    }
}