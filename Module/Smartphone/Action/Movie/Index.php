<?php
/**
 * 
 */
namespace X\Module\Smartphone\Action\Movie;

/**
 * 
 */
use X\Module\Smartphone\Util\Action\Visual;

/**
 * 
 */
class Index extends Visual {
    /**
     * 
     */
    public function runAction( $mark ) {
        $movieService = $this->getMovieService();
        $movie = $movieService->getUnvisitedAndUnmarkedMovie(null, 1, 1);
        if ( empty($movie) ) {
            return;
        }
        $movie= $movie[0];
        if ( 0 === (int)$movie['has_cover'] ) {
            $movie['cover'] = $movieService->getMediaDefaultCoverURL();
        } else {
            $movie['cover'] = $movieService->getCoverURL($movie['id']);
        }
        
        $view = $this->getView();
        $viewName = 'SMARTPHONE_MOVIE_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/Index');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'movie', $movie);
        
        $view->title = $movie['name'];
    }
}