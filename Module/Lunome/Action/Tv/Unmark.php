<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Tv;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Basic;

/**
 * The action class for movie/ignore action.
 * @author Unknown
 */
class Unmark extends Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id, $mark ) {
        $this->getTvService()->unmark($id, $mark);
        $this->goBack();
    }
}