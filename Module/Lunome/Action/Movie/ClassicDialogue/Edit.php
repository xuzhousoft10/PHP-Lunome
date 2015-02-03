<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie\ClassicDialogue;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Basic;

/**
 * The action class for movie/classicDialogue/edit action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Edit extends Basic { 
    /**
     * @param string $id
     * @param string $content
     */
    public function runAction( $id, $content ) {
        $movieService = $this->getMovieService();
        
        if ( $movieService->has($id) ) {
            return;
        }
        $movieService->addClassicDialogues($id, $content);
        echo json_encode(array('status'=>'success'));
    }
}