<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie;

/**
 * 
 */
use X\Module\Backend\Util\Action\Basic;

/**
 * 
 */
class DeleteCover extends Basic {
    /**
     * 
     */
    public function runAction($movie) {
        $movieService = $this->getMovieService();
        if ( !$movieService->has($movie) ) {
            $this->throw404();
        }
        
        $movieService->deleteCover($movie);
        $this->goBack();
    }
}