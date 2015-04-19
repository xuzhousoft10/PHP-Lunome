<?php
namespace X\Module\Movie\Action\Comment;
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
    public function runAction( $movie, $comment, $vote ) {
        $comment = $this->getMovie()->getShortCommentManager()->get($comment);
        if ( null === $comment ) {
            return $this->throw404();
        }
        
        $voteManager = $comment->getVoteManager();
        if ( 'up' === $vote ) {
            $voteManager->voteUp();
        } else {
            $voteManager->voteDown();
        }
        
        $this->goBack();
    }
}