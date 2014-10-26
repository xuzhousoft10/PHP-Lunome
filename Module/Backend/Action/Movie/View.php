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
class View extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id=null ) {
        $this->setActiveItem(self::MENU_MOVIE_MANAGEMENT);
        
        /* @var $service MovieService */
        $service = X::system()->getServiceManager()->get(MovieService::getServiceName());
        $media = $service->get($id);
        
        $name   = 'MOVIE_VIEW';
        $path   = $this->getParticleViewPath('Movie/View');
        $option = array();
        $data   = array('media'=>$media);
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}