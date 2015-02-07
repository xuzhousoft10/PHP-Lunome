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
class Remove extends Basic {
    /**
     * 
     */
    public function runAction($movie, $actor) {
        $movieService = $this->getMovieService();
        if ( !$movieService->has($movie) ) {
            $this->throw404();
        }
        
        $movieService->removeActorFromMovie($movie, $actor);
        $this->goBack();
    }
}