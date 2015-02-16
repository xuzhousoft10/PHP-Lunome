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
class Index extends Visual {
    /**
     * 
     */
    public function runAction( $page=1 ) {
        $view = $this->getView();
        $movieService = $this->getMovieService();
        $moduleConfig = $this->getModule()->getConfiguration();
        $page = (int)$page;
        $pageSize = $moduleConfig->get('movie_region_index_page_size');
        $regions = $movieService->getRegions(($page-1)*$pageSize, $pageSize);
        
        $viewName = 'BACKEND_MOVIE_REGION_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/Regions');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'regions', $regions);
        
        $viewName = 'BACKEND_MOVIE_REGION_INDEX_PAGER';
        $viewPath = $this->getParticleViewPath('Util/Pager');
        $view->loadParticle($viewName, $viewPath);
        $totalCount = $movieService->countRegions();
        $view->setDataToParticle($viewName, 'totalCount', $totalCount);
        $view->setDataToParticle($viewName, 'pageSize', $pageSize);
        $view->setDataToParticle($viewName, 'currentPage', $page);
        $pagerParams = array('module'=>'backend', 'action'=>'movie/region/index');
        $view->setDataToParticle($viewName, 'parameters', http_build_query($pagerParams));
        
        $this->setPageTitle('电影区域管理');
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}