<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Poster;

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
    public function runAction($id, $page=1) {
        $moduleConfig = $this->getModule()->getConfiguration();
        $movieService = $this->getMovieService();
        $view = $this->getView();
        $page = (int)$page;
        $pageSize = $moduleConfig->get('movie_poster_index_page_size');
        
        if ( !$movieService->has($id) ) {
            $this->throw404();
        }
        
        $movie = $movieService->get($id);
        $posters = $movieService->getPosters($id, ($page-1)*$pageSize, $pageSize);
        foreach ( $posters as $index => $poster ) {
            $posters[$index] = $poster->toArray();
            $posters[$index]['image'] = $movieService->getPosterUrlById($poster->id);
        }
        
        $viewName = 'BACKEND_MOVIE_POSTER_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/Posters');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'posters', $posters);
        $view->setDataToParticle($viewName, 'movie', $movie);
        
        $viewName = 'BACKEND_MOVIE_DIALOGUE_INDEX_PAGER';
        $viewPath = $this->getParticleViewPath('Util/Pager');
        $view->loadParticle($viewName, $viewPath);
        $totalCount = $movieService->countPosters($id);
        $view->setDataToParticle($viewName, 'totalCount', $totalCount);
        $view->setDataToParticle($viewName, 'pageSize', $pageSize);
        $view->setDataToParticle($viewName, 'currentPage', $page);
        $pagerParams = array_merge(array('module'=>'backend', 'action'=>'movie/poster/index'), array('id'=>$id));
        $view->setDataToParticle($viewName, 'parameters', http_build_query($pagerParams));
        
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}