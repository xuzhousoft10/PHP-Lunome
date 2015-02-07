<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Actor;

/**
 * 
 */
use X\Module\Backend\Util\Action\Basic;

/**
 * 
 */
class Add extends Basic {
    /**
     * 
     */
    public function runAction($movie, $name) {
        $movieService = $this->getMovieService();
        if ( !$movieService->has($movie) ) {
            $this->throw404();
        }
        
        $movieService->addActorToMovie($movie, $name);
        $this->goBack();
    }
}