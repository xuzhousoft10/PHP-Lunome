<?php
/**
 * 
 */
namespace X\Module\Backend\Util\Action\Media\Poster;

/**
 * The action class for movie/poster/view action.
 * @author Unknown
 */
class Delete extends Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id ) {
        $this->getMediaService()->deletePoster($id);
        $this->goBack();
    }
}