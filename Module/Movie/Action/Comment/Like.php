<?php
namespace X\Module\Movie\Action\Comment;
/**
 * 
 */
use X\Module\Movie\Util\Action\MovieAttributeBasicAction;
/**
 *
 */
class Like extends MovieAttributeBasicAction {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $movie, $comment, $like ) {
        $comment = $this->getMovie()->getShortCommentManager()->get($comment);
        if ( null === $comment ) {
            return $this->throw404();
        }
        
        $favouriteManager = $comment->getFavouriteManager();
        if ( 'yes' === $like ) {
            $favouriteManager->mark();
        } else {
            $favouriteManager->unmark();
        }
        
        $this->goBack();
    }
}