<?php
/**
 * The action file for movie/index action.
 */
namespace X\Module\Backend\Action\Movie;

/**
 * 
 */
use X\Core\X;
use X\Module\Backend\Util\Action\MediaIndex;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/index action.
 * @author Unknown
 */
class Index extends MediaIndex { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $page=1 ) {
        $this->setActiveItem(self::MENU_MOVIE_MANAGEMENT);
        
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
        $medias = $movieService->findAll(null, ($page-1)*20, 10);
        
        /* Load index view. */
        $name   = 'MOVIE_INDEX';
        $path   = $this->getParticleViewPath('Movie/Index');
        $option = array();
        $data   = array('medias'=>$medias);
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        /* Load pager view */
        $count = $movieService->count();
        $name   = 'UTIL_PAGER';
        $path   = $this->getParticleViewPath('Util/Pager');
        $option = array();
        $data   = array('total'=>$count, 'current'=>$page, 'size'=>20, 'url'=>'/index.php?module=backend&action=movie/index&page=%d');
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}