<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie;

/**
 * 
 */
use X\Core\X;
use X\Module\Backend\Util\Action\Visual;
use X\Module\Lunome\Service\Region\Service as RegionService;

/**
 * 
 */
class Detail extends Visual {
    /**
     * 
     */
    public function runAction($id) {
        $view = $this->getView();
        $movieService = $this->getMovieService();
        /* @var $regionService RegionService */
        $regionService = X::system()->getServiceManager()->get(RegionService::getServiceName());
        
        if ( !$movieService->has($id) ) {
            $this->throw404();
        }
        
        $movie = $movieService->get($id);
        if ( 1 === (int)$movie['has_cover'] ) {
            $movie['cover'] = $movieService->getCoverURL($movie['id']);
        } else {
            $movie['cover'] = $movieService->getMediaDefaultCoverURL();
        }
        
        $movie['language'] = $movieService->getLanguageById($movie['language_id'])->name;
        $movie['region'] = $movieService->getRegionById($movie['region_id'])->name;
        $movie['length'] = ((int)($movie['length']/3600)).'小时'.((int)($movie['length']%60)).'分钟';
        
        $viewName = 'BACKEND_MVOIE_DETAIL';
        $viewPath = $this->getParticleViewPath('Movie/Detail');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'movie', $movie);
        $view->setDataToParticle($viewName, 'actors', $movieService->getActors($movie['id']));
        
        
        $this->setPageTitle($movie['name']);
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}