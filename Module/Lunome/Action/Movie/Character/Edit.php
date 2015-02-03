<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie\Character;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Basic;

/**
 * The action class for movie/character/edit action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Edit extends Basic { 
    /**
     * @param string $movie
     * @param array $character
     */
    public function runAction( $movie, $character ) {
        $movieService = $this->getMovieService();
        
        if ( !$movieService->has($movie) ) {
            return;
        }
        
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