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
class Vote extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $movie, $character, $vote ) {
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
        
        $voteManager = $character->getVoteManager();
        if ( 'up' === $vote ) {
            $voteManager->voteUp();
        } else {
            $voteManager->voteDown();
        }
        
        $this->goBack();
    }
}