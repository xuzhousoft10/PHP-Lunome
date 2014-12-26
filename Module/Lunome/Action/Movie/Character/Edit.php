<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie\Character;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/ignore action.
 * @author Unknown
 */
class Edit extends Basic { 
    /**
     * @param unknown $id
     * @param unknown $content
     */
    public function runAction( $movie, $character ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        
        $image = null;
        if ( isset($_FILES['image']) && 0===$_FILES['image']['error'] ) {
            $tmpname = tempnam(sys_get_temp_dir(), 'UPCI');
            move_uploaded_file($_FILES['image']['tmp_name'], $tmpname);
            $image = $tmpname;
        }
        $movieService->addCharacter($movie, $character, $image);
        if ( null !== $image ) {
            unlink($image);
        }
        echo json_encode(array('status'=>'ok'));
    }
}