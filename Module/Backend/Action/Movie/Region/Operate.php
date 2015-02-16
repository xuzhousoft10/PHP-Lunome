<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Region;

/**
 * 
 */
use X\Module\Backend\Util\Action\Visual;

/**
 * 
 */
class Operate extends Visual {
    /**
     * 
     */
    public function runAction( $id ) {
        $view = $this->getView();
        $movieService = $this->getMovieService();
        $region = $movieService->getRegionById($id);
        if ( null === $region ) {
            $this->throw404();
        }
        
        $viewName = 'BACKEND_MOVIE_REGION_OPERATE_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/RegionOperate');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'region', $region);
        $view->setDataToParticle($viewName, 'allRegions', $movieService->getRegions());
        
        $this->setPageTitle('电影区域操作');
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}