<?php
namespace X\Module\Movie\Action\Poster;
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
    public function runAction( $movie, $poster, $content ) {
        $poster = $this->getMovie()->getPosterManager()->get($poster);
        if ( null === $poster ) {
            return $this->throw404();
        }
        
        $commentManager = $poster->getCommentManager();
        $commentManager->add($content);
        
        $this->goBack();
    }
}