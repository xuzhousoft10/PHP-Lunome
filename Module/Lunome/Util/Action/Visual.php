<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action;

/**
 * Visual action class
 */
abstract class Visual extends \X\Util\Action\Visual {
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeRunAction()
     */
    protected function beforeRunAction() {
        parent::beforeRunAction();
        
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
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::afterRunAction()
     */
    protected function afterRunAction() {
        /* Load footer view */
        $name   = 'FOOTER';
        $path   = $this->getParticleViewPath('Util/Footer');
        $option = array('zone'=>'footer');
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        parent::afterRunAction();
    }
}