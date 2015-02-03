<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie\Comment;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Basic;

/**
 * The action class for movie/comment/add action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Add extends Basic { 
    /**
     * @param string $id
     * @param string $content
     */
    public function runAction( $id, $content ) {
        $movieService = $this->getMovieService();
        
        if ( !$movieService->has($id) ) {
            return;
        }
        
        if ( empty($content) ) {
            return;
        }
        
        $movieService->addShortComment($id, $content);
    }
}