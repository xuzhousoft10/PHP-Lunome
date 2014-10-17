<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action;

/**
 * Visual action class
 * 
 * @method \X\Module\Lunome\Service\User\Service getUserService()
 * @method \X\Module\Lunome\Service\Movie\Service getMovieService()
 * @method \X\Module\Lunome\Service\Tv\Service getTvService()
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
        $data   = array('user'=>$this->getCurrentUserData());
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
    
    protected function getCurrentUserData() {
        $userData = array();
        $userData['isGuest'] = $this->getUserService()->getIsGuest();
        if ( !$userData['isGuest'] ) {
            $data = $this->getUserService()->getCurrentUser();
            $userData['id']         = $data['ID'];
            $userData['nickname']   = $data['NICKNAME'];
            $userData['photo']      = $data['PHOTO'];
            $userData['account']    = $data['ACCOUNT'];
        }
        return $userData;
    }
}