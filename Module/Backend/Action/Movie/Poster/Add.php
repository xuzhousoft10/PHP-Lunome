<?php
/**
 * The action file for movie/poster/view action.
 */
namespace X\Module\Backend\Action\Movie\Poster;

/**
 * 
 */
use X\Core\X;
use X\Module\Backend\Util\Action\Basic;
use X\Module\Lunome\Service\Movie\Service as MovieService;

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
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
        if( 0 === $_FILES['poster']['error'] ) {
            $tempPoster = tempnam(sys_get_temp_dir(), 'POS');
            move_uploaded_file($_FILES['poster']['tmp_name'], $tempPoster);
            $movieService->addPoster($id, file_get_contents($tempPoster));
            unlink($tempPoster);
        }
        $this->goBack();
    }
}