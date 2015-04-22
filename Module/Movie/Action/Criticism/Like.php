<?php
namespace X\Module\Movie\Action\Criticism;
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
    public function runAction( $movie, $criticism, $like ) {
        $criticism = $this->getMovie()->getCriticismManager()->get($criticism);
        if ( null === $criticism ) {
            return $this->throw404();
        }
        
        $favouriteManager = $criticism->getFavouriteManager();
        if ( 'yes' === $like ) {
            $favouriteManager->mark();
        } else {
            $favouriteManager->unmark();
        }
        
        $this->goBack();
    }
}