<?php
/**
 * The action file for movie/poster/view action.
 */
namespace X\Module\Backend\Action\Movie\Poster;

/**
 * 
 */
use X\Core\X;
use X\Module\Backend\Util\Action\Visual;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/poster/view action.
 * @author Unknown
 */
class Index extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id ) {
        $this->setActiveItem(self::MENU_MOVIE_MANAGEMENT);
        
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
        $hasPoster = $movieService->hasPoster($id);
        
        $name = 'POSTER_INDEX';
        $path = $this->getParticleViewPath('Movie/Poster/Index');
        $option = array();
        $data = array('mediaId'=>$id, 'returnURL'=>$this->getReferer(), 'hasPoster'=>$hasPoster);
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}