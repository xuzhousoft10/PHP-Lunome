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
class Detail extends Visual {
    /**
     * 
     */
    public function runAction($id) {
        $view = $this->getView();
        $movieService = $this->getMovieService();
        if ( !$movieService->has($id) ) {
            $this->throw404();
        }
        
        $movie = $movieService->get($id);
        
        $viewName = 'BACKEND_MVOIE_DETAIL';
        $viewPath = $this->getParticleViewPath('Movie/Detail');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'movie', $movie);
        
        $this->setPageTitle($movie['name']);
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}