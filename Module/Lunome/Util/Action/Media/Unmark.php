<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action\Media;

/**
 * Visual action class
 */
abstract class Unmark extends Basic {
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id, $mark ) {
        $this->getMediaService()->unmark($id, $mark);
        $this->goBack();
    }
}