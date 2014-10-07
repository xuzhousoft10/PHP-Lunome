<?php
/**
 * The action file for movie/index action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\VisualMain;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/index action.
 * @author Unknown
 */
class Index extends VisualMain { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        /* @var $service \X\Module\Lunome\Service\Movie\Service */
        $service = X::system()->getServiceManager()->get(MovieService::getServiceName());
        $movies = $service->getUnmarkedMovies();
        
        $name   = 'MOVIE_INDEX';
        $path   = $this->getParticleViewPath('Movie/Index');
        $option = array(); 
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}