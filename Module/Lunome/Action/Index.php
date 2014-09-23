<?php
/**
 * The action file for index action.
 */
namespace X\Module\Lunome\Action;

/**
 * Use statements
 */
use X\Util\Action\Visual;
use X\Service\XView\Core\Handler\Html;

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
        
        /* Load navigation bar */
        $name   = 'INDEX_NAV_BAR';
        $path   = $this->getParticleViewPath('Util/Navigation');
        $option = array('zone'=>'header');
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        /* Load User Board */
        $name   = 'USER_BOARD';
        $path   = $this->getParticleViewPath('User/Board');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        /* Load movie particle view. */
        $name   = 'INDEX_MOVIE';
        $path   = $this->getParticleViewPath('Movie/Index');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}