<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Language;

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
        $pageSize = $moduleConfig->get('movie_language_index_page_size');
        $languages = $movieService->getLanguages(($page-1)*$pageSize, $pageSize);
        
        $viewName = 'BACKEND_MOVIE_LANGUAGE_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/Languages');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'languages', $languages);
        
        $viewName = 'BACKEND_MOVIE_CATEGORY_INDEX_PAGER';
        $viewPath = $this->getParticleViewPath('Util/Pager');
        $view->loadParticle($viewName, $viewPath);
        $totalCount = $movieService->countLanguage();
        $view->setDataToParticle($viewName, 'totalCount', $totalCount);
        $view->setDataToParticle($viewName, 'pageSize', $pageSize);
        $view->setDataToParticle($viewName, 'currentPage', $page);
        $pagerParams = array('module'=>'backend', 'action'=>'movie/language/index');
        $view->setDataToParticle($viewName, 'parameters', http_build_query($pagerParams));
        
        $this->setPageTitle('电影语言管理');
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}