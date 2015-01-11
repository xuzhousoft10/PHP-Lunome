<?php
/**
 * 
 */
namespace X\Module\Lunome\Action\User\Interaction;

/**
 * 
 */
use X\Module\Lunome\Util\Action\Userinteraction;

/**
 * 
 */
class Index extends Userinteraction {
    /**
     * @param unknown $id
     */
    public function runAction( $id ) {
        $friendInformation = $this->userService->getAccount()->getInformation($id);
        $this->interactionMenuParams = array('id'=>$id);
        
        /* Main */
        $name   = 'USER_INTERACTION_INDEX';
        $path   = $this->getParticleViewPath('User/Interaction/Index');
        $option = array();
        $data   = array(
            'items'         => $this->interactionMenu, 
            'parameters'    => $this->interactionMenuParams,
        );
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->title = '与'.$friendInformation->nickname.'互动';
    }
}