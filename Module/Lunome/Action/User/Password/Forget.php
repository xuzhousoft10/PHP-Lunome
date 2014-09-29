<?php
/**
 * This file handles the user/login action
 */
namespace X\Module\Lunome\Action\User\Password;

/**
 * Use statements
 */
use X\Util\Action\Visual;
use X\Service\XView\Core\Handler\Html;

/**
 * The user/login action class.
 *
 * @author Michael Luthor
 */
class Forget extends Visual {
    public function runAction( $username, $password ) {
        /* Load layout. */
        $this->getView()->loadLayout(Html::LAYOUT_SINGLE_COLUMN_FULL_WIDTH);
        
        /* Load login particle view. */
        $name   = 'PASSWORD_FORGET';
        $path   = $this->getParticleViewPath('User/Password/Forget');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}