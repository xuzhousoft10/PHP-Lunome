<?php
/**
 * The action file for movie/poster/view action.
 */
namespace X\Module\Backend\Util\Action\Media\Poster;

/**
 * The action class for movie/poster/view action.
 * @author Unknown
 */
class Add extends Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id ) {
        if( 0 === $_FILES['poster']['error'] ) {
            $tempPoster = tempnam(sys_get_temp_dir(), 'POS');
            move_uploaded_file($_FILES['poster']['tmp_name'], $tempPoster);
            $this->getMediaService()->addPoster($id, file_get_contents($tempPoster));
            unlink($tempPoster);
        }
        $this->goBack();
    }
}