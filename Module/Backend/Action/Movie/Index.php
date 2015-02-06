<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie;

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
    public function runAction($condition=null, $page=1) {
        $view = $this->getView();
        $movieService = $this->getMovieService();
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = (int)$moduleConfig->get('movie_index_page_size');
        $condition = ( empty($condition) || !is_array($condition) ) ? array() : $condition;
        $condition = array_filter($condition);
        $page = (int)$page;
        
        $movies = $movieService->findAll($condition, ($page-1)*$pageSize, $pageSize);
        
        $viewName = 'BACKEND_MOVIE_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/Index');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'movies', $movies);
        $view->setDataToParticle($viewName, 'condition', $condition);
        
        $viewName = 'BACKEND_MVOIE_INDEX_PAGER';
        $viewPath = $this->getParticleViewPath('Util/Pager');
        $view->loadParticle($viewName, $viewPath);
        $totalCount = $movieService->count($condition);
        $view->setDataToParticle($viewName, 'totalCount', $totalCount);
        $view->setDataToParticle($viewName, 'pageSize', $pageSize);
        $view->setDataToParticle($viewName, 'currentPage', $page);
        $pagerParams = array_merge(array('module'=>'backend', 'action'=>'movie/index'), array('condition'=>$condition));
        $view->setDataToParticle($viewName, 'parameters', http_build_query($pagerParams));
        
        $this->setPageTitle('电影管理');
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}