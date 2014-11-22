<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action\Media;

/**
 * Visual action class
 */
abstract class Mark extends Basic {
    protected $mediaId;
    protected $mark;
    
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id, $mark ) {
        $this->mediaId = $id;
        $this->mark = $mark;
        $this->getMediaService()->mark($id, $mark);
    }
}