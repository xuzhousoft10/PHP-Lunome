<?php
/**
 * The action file for connectus action.
 */
namespace X\Module\Lunome\Action;

/**
 * 
 */
use X\Module\Lunome\Util\Action\Visual;

/**
 * The action class for connectus action.
 * @author Unknown
 */
class History extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        $this->getView()->title = "更新历史 | Lunome";
        
        $this->getView()->loadLayout($this->getLayoutViewPath('BlankThin'));
        
        /* Load particle view. */
        $name   = 'UPDATE_HISTORY';
        $path   = $this->getParticleViewPath('History/Index');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}