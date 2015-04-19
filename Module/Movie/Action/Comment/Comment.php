<?php
namespace X\Module\Movie\Action\Comment;
/**
 * 
 */
use X\Module\Movie\Util\Action\MovieAttributeBasicAction;
/**
 *
 */
class Comment extends MovieAttributeBasicAction {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $movie, $comment, $content ) {
        $comment = $this->getMovie()->getShortCommentManager()->get($comment);
        if ( null === $comment ) {
            return $this->throw404();
        }
        
        $comment->getCommentManager()->add($content);
        $this->goBack();
    }
}