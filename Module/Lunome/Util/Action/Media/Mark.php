<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action\Media;

/**
 * Visual action class
 */
abstract class Mark extends Basic {
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id, $mark ) {
        $this->getMediaService()->mark($id, $mark);
        $this->goBack();
    }
}