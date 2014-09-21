<?php
/**
 * This file handles the user/login action
 */
namespace X\Module\Lunome\Action\User;

/**
 * Use statements
 */
use X\Util\Action\Visual;

/**
 * The user/login action class.
 * 
 * @author Michael Luthor
 */
class Login extends Visual { 
    public function runAction( $username, $password ) {
        /* Load login particle view. */
        $name   = 'LOGIN';
        $path   = $this->getParticleViewPath('User/Login');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}