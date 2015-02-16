<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Director;

/**
 * 
 */
use X\Module\Backend\Util\Action\Basic;

/**
 * 
 */
class Remove extends Basic {
    /**
     * 
     */
    public function runAction($movie, $director) {
        $movieService = $this->getMovieService();
        if ( !$movieService->has($movie) ) {
            $this->throw404();
        }
        
        $movieService->removeDirectorFromMovie($movie, $director);
        $this->goBack();
    }
}