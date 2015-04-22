<?php
namespace X\Module\Movie\Action\Criticism;
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
    public function runAction( $movie, $criticism, $content ) {
        $criticism = $this->getMovie()->getCriticismManager()->get($criticism);
        if ( null === $criticism ) {
            return $this->throw404();
        }
        
        $criticism->getCommentManager()->add($content);
        $this->goBack();
    }
}