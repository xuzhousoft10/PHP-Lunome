<?php
namespace X\Module\Movie\Action\Criticism;
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
    public function runAction( $movie, $criticism, $vote ) {
        $criticism = $this->getMovie()->getCriticismManager()->get($criticism);
        if ( null === $criticism ) {
            return $this->throw404();
        }
        
        $voteManager = $criticism->getVoteManager();
        if ( 'up' === $vote ) {
            $voteManager->voteUp();
        } else {
            $voteManager->voteDown();
        }
        
        $this->goBack();
    }
}