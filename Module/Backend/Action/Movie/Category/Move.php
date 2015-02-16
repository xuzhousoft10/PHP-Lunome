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
class Move extends Basic {
    /**
     * 
     */
    public function runAction($from, $to) {
        $movieService = $this->getMovieService();
        $movieService->moveMoviesFromACategoryToAnotherCategory($from, $to);
        $this->goBack();
    }
}