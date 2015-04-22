<?php
namespace X\Module\Movie\Action\Poster;
/**
 * 
 */
use X\Module\Movie\Util\Action\MovieAttributeBasicAction;
/**
 *
 */
class Vote extends MovieAttributeBasicAction {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $movie, $poster, $vote ) {
        $poster = $this->getMovie()->getPosterManager()->get($poster);
        if ( null === $poster ) {
            return $this->throw404();
        }
        
        $voteManager = $poster->getVoteManager();
        if ( 'up' === $vote ) {
            $voteManager->voteUp();
        } else {
            $voteManager->voteDown();
        }
        
        $this->goBack();
    }
}