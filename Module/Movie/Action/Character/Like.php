<?php
namespace X\Module\Movie\Action\Character;
/**
 * 
 */
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Movie\Service\Movie\Service as MovieService;
/**
 *
 */
class Like extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $movie, $character, $like ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        
        $movie = $movieService->get($movie);
        if ( null === $movie ) {
            return $this->throw404();
        }
        
        $character = $movie->getCharacterManager()->get($character);
        if ( null === $character ) {
            return $this->throw404();
        }
        
        $favouriteManager = $character->getFavouriteManager();
        if ( 'yes' === $like ) {
            $favouriteManager->mark();
        } else {
            $favouriteManager->unmark();
        }
        
        $this->goBack();
    }
}