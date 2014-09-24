<?php
/**
 * The action file for user/information/basic action.
 */
namespace X\Module\Lunome\Action\User\Information;

/**
 * Use statements
 */
use X\Util\Action\Visual;
use X\Service\XView\Core\Handler\Html;

/**
 * The action class for user/information/basic action.
 * @author Unknown
 */
class Basic extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        /* Load layout. */
        $this->getView()->loadLayout(Html::LAYOUT_TWO_COLUMNS);
        
        /* Load navigation bar */
        $name   = 'INDEX_NAV_BAR';
        $path   = $this->getParticleViewPath('Util/Navigation');
        $option = array('zone'=>'header');
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        /* Load User Board */
        $name   = 'USER_BOARD';
        $path   = $this->getParticleViewPath('User/Board');
        $option = array('zone'=>'header');
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        /* Load information menu. */
        $name   = 'INFORMATION_MENU';
        $path   = $this->getParticleViewPath('User/Information/Menu');
        $option = array('zone'=>'left');
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        /* Load footer view */
        $name   = 'FOOTER';
        $path   = $this->getParticleViewPath('Util/Footer');
        $option = array('zone'=>'footer');
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}