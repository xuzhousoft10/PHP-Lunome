<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Region;

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
        $movieService->moveMoviesFromARegionToAnother($from, $to);
        $this->goBack();
    }
}