<?php
/**
 * The action file for index action.
 */
namespace X\Module\Lunome\Action;

/**
 * Use statements
 */
use X\Service\XView\Core\Handler\Html;
use X\Module\Lunome\Util\Action\Visual;

/**
 * The action class for index action.
 * @author Unknown
 */
class Index extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( /* @TODO Add parameters here if you need. */ ) {
        /* Load layout. */
        $this->getView()->loadLayout(Html::LAYOUT_SINGLE_COLUMN);
        
        /* Load movie particle view. */
        $name   = 'INDEX_MOVIE';
        $path   = $this->getParticleViewPath('Movie/Index');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        /* Load movie particle view. */
        $name   = 'INDEX_TV';
        $path   = $this->getParticleViewPath('Tv/Index');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        /* Load movie particle view. */
        $name   = 'INDEX_BOOK';
        $path   = $this->getParticleViewPath('Book/Index');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}