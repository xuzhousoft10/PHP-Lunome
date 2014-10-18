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
class Connectus extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        $this->getView()->title = "联系我们 | Lunome";
        
        $this->getView()->loadLayout($this->getLayoutViewPath('Blank'));
        
        /* Load particle view. */
        $name   = 'CONNECT_US';
        $path   = $this->getParticleViewPath('ConnectUs/Index');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}