<?php
/**
 * 
 */
namespace X\Module\Backend\Util\Action\Media\Poster;

/**
 * The action class for movie/poster/view action.
 * @author Unknown
 */
class Download extends Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id ) {
        $content = $this->getMediaService()->getPoster($id);
        header("content-type:image/jpeg");
        echo $content;
    }
}