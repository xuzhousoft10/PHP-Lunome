<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Basic;

/**
 * The action class for movie/ignore action.
 * @author Unknown
 */
class Mark extends Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id, $mark ) {
        $this->getMovieService()->mark($id, $mark);
        $this->goBack();
    }
}