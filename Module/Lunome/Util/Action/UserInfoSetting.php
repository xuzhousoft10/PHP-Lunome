<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action;

/**
 * User information setting action.
 */
abstract class UserInfoSetting extends Visual {
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Visual::beforeRunAction()
     */
    protected function beforeRunAction() {
        parent::beforeRunAction();
        
        /* Load information menu. */
        $name   = 'INFORMATION_MENU';
        $path   = $this->getParticleViewPath('User/Information/Menu');
        $option = array('zone'=>'left');
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}