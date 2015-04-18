<?php
namespace X\Module\Movie\Action\Dialogue;
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
    public function runAction( $movie, $dialogue, $vote ) {
        $dialogue = $this->getMovie()->getClassicDialogueManager()->get($dialogue);
        if ( null === $dialogue ) {
            return $this->throw404();
        }
        
        $voteManager = $dialogue->getVoteManager();
        if ( 'up' === $vote ) {
            $voteManager->voteUp();
        } else {
            $voteManager->voteDown();
        }
        
        $this->goBack();
    }
}