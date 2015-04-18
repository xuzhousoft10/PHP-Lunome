<?php
namespace X\Module\Movie\Action\Dialogue;
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
    public function runAction( $movie, $dialogue, $like ) {
        $dialogue = $this->getMovie()->getClassicDialogueManager()->get($dialogue);
        if ( null === $dialogue ) {
            return $this->throw404();
        }
        
        $favouriteManager = $dialogue->getFavouriteManager();
        if ( 'yes' === $like ) {
            $favouriteManager->mark();
        } else {
            $favouriteManager->unmark();
        }
        
        $this->goBack();
    }
}