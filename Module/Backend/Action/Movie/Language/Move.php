<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Language;

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
        $movieService->moveMoviesFromALanguageToAnother($from, $to);
        $this->goBack();
    }
}