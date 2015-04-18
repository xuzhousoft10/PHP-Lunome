<?php
namespace X\Module\Movie\Action\Dialogue;
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
    public function runAction( $movie, $dialogue, $content ) {
        $dialogue = $this->getMovie()->getClassicDialogueManager()->get($dialogue);
        if ( null === $dialogue ) {
            return $this->throw404();
        }
        
        $commentManager = $dialogue->getCommentManager();
        $commentManager->add($content);
        
        $this->goBack();
    }
}