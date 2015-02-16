<?php
/**
 * 
 */
namespace X\Module\Smartphone\Action\Movie;

/**
 * 
 */
use X\Module\Smartphone\Util\Action\Visual;
use X\Module\Lunome\Service\Movie\Service as MovieService;

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
        
        $mark = (int)$mark;
        $markActions = array();
        switch ( $mark ) {
        case MovieService::MARK_UNMARKED:
            $markActions[MovieService::MARK_INTERESTED] = '想看';
            $markActions[MovieService::MARK_WATCHED] = '已看';
            $markActions[MovieService::MARK_IGNORED] = '忽略';
            break;
        default:
            break;
        }
        
        $view = $this->getView();
        $viewName = 'SMARTPHONE_MOVIE_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/Index');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'movie', $movie);
        $view->setDataToParticle($viewName, 'markActions', $markActions);
        
        $view->title = $movie['name'];
        $movieService->markMovieAsVisited($movie['id']);
    }
}