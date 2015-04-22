<?php
namespace X\Module\Movie\Action\Poster;
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
    public function runAction( $movie, $poster, $like ) {
        $poster = $this->getMovie()->getPosterManager()->get($poster);
        if ( null === $poster ) {
            return $this->throw404();
        }
        
        $favouriteManager = $poster->getFavouriteManager();
        if ( 'yes' === $like ) {
            $favouriteManager->mark();
        } else {
            $favouriteManager->unmark();
        }
        
        $this->goBack();
    }
}