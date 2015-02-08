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
        if ( 1 === (int)$movie['has_cover'] ) {
            $movie['cover'] = $movieService->getCoverURL($movie['id']);
        } else {
            $movie['cover'] = $movieService->getMediaDefaultCoverURL();
        }
        
        $movie['language'] = $movieService->getLanguageById($movie['language_id']);
        $movie['language'] = empty($movie['language']) ? '' : $movie['language']->name;
        $movie['region'] = $movieService->getRegionById($movie['region_id']);
        $movie['region'] = empty($movie['region']) ? '' : $movie['region']->name;
        $movie['length'] = ((int)($movie['length']/3600)).'小时'.((int)($movie['length']%60)).'分钟';
        
        $categories = $movieService->getCategoriesByMovieId($movie['id']);
        $selectedCategories = array();
        foreach ( $categories as $category ) {
            $selectedCategories[] = $category->id;
        }
        $unselectedCategories = $movieService->getCategories();
        foreach ( $unselectedCategories as $index => $category ) {
            if ( in_array($category->id, $selectedCategories) ) {
                unset($unselectedCategories[$index]);
            }
        }
        
        $viewName = 'BACKEND_MVOIE_DETAIL';
        $viewPath = $this->getParticleViewPath('Movie/Detail');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'movie', $movie);
        $view->setDataToParticle($viewName, 'actors', $movieService->getActors($movie['id']));
        $view->setDataToParticle($viewName, 'categories', $categories);
        $view->setDataToParticle($viewName, 'directors', $movieService->getDirectors($movie['id']));
        $view->setDataToParticle($viewName, 'unselectedCategories', $unselectedCategories);
        
        $this->setPageTitle($movie['name']);
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}