<?php
/**
 * The action file for book/unread action.
 */
namespace X\Module\Lunome\Action\Book;

/**
 * Use statements
 */
use X\Service\XView\Core\Handler\Html;
use X\Module\Lunome\Util\Action\Visual;

/**
 * The action class for book/unread action.
 * @author Unknown
 */
class Unread extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        /* Load layout. */
        $this->getView()->loadLayout(Html::LAYOUT_SINGLE_COLUMN);
        
        /* Load movie particle view. */
        $name   = 'UNWATCHED_MOVIE';
        $path   = $this->getParticleViewPath('Book/Unread');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}