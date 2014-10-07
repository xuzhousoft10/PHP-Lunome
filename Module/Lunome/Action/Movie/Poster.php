<?php
/**
 * The action file for movie/poster action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Basic;

/**
 * The action class for movie/poster action.
 * @author Unknown
 */
class Poster extends Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id ) {
        $content = $this->getMovieService()->getPoster($id);
        echo $content;
    }
}