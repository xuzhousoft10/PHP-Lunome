<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Category;

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
        $pageSize = $moduleConfig->get('movie_category_index_page_size');
        $categories = $movieService->getCategories(($page-1)*$pageSize, $pageSize);
        
        $viewName = 'BACKEND_MOVIE_CATEGORY_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/Categories');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'categories', $categories);
        
        $viewName = 'BACKEND_MOVIE_CATEGORY_INDEX_PAGER';
        $viewPath = $this->getParticleViewPath('Util/Pager');
        $view->loadParticle($viewName, $viewPath);
        $totalCount = $movieService->countCategory();
        $view->setDataToParticle($viewName, 'totalCount', $totalCount);
        $view->setDataToParticle($viewName, 'pageSize', $pageSize);
        $view->setDataToParticle($viewName, 'currentPage', $page);
        $pagerParams = array('module'=>'backend', 'action'=>'movie/category/index');
        $view->setDataToParticle($viewName, 'parameters', http_build_query($pagerParams));
        
        $this->setPageTitle('电影类型管理');
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}