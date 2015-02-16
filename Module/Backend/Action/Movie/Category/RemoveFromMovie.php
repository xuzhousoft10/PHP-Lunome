<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Category;

/**
 * 
 */
use X\Module\Backend\Util\Action\Basic;

/**
 * 
 */
class RemoveFromMovie extends Basic {
    /**
     * 
     */
    public function runAction($movie, $category) {
        $movieService = $this->getMovieService();
        if ( !$movieService->has($movie) ) {
            $this->throw404();
        }
        
        $movieService->removeCategoryFromMovie($movie, $category);
        $this->goBack();
    }
}