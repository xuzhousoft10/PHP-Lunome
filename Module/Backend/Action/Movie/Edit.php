<?php
/**
 * The action file for movie/index action.
 */
namespace X\Module\Backend\Action\Movie;

/**
 * 
 */
use X\Core\X;
use X\Module\Backend\Util\Action\Visual;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/index action.
 * @author Unknown
 */
class Edit extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id=null, $save=false ) {
        $this->setActiveItem(self::MENU_MOVIE_MANAGEMENT);
        
        $media = array('id'=>null, 'name'=>'');
        /* @var $service MovieService */
        $service = X::system()->getServiceManager()->get(MovieService::getServiceName());
        if ( null !== $id ) {
            $media = $service->get($id);
        }
        
        if ( false !== $save ) {
            $media = $_POST['media'];
            $media = (null === $id) ? $service->add($media) : $service->update($id, $media);
            $this->gotoURL('/index.php?module=backend&action=movie/index');
        }
        
        $name   = 'MOVIE_EDIT';
        $path   = $this->getParticleViewPath('Movie/Edit');
        $option = array();
        $data   = array('media'=>$media);
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}