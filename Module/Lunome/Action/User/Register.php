<?php
/**
 * The action file to handle register.
 */
namespace X\Module\Lunome\Action\User;

/**
 * Use statements
 */
use X\Util\Action\Visual;
use X\Service\XView\Core\Handler\Html;

/**
 * The action class for register action.
 * 
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Register extends Visual { 
    /**
     * Execute the registion.
     * 
     * @return void
     */
    public function runAction( $email, $password ) {
        /* Load layout. */
        $this->getView()->loadLayout(Html::LAYOUT_SINGLE_COLUMN_FULL_WIDTH);
        
        /* Load login particle view. */
        $name   = 'REGISTER';
        $path   = $this->getParticleViewPath('User/Register');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}