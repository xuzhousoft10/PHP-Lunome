<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie\Poster;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/poster/index action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Index extends Visual { 
    /**
     * @param string $id
     * @param integer $page
     */
    public function runAction( $id, $page=1 ) {
        $moduleConfig = $this->getModule()->getConfiguration();
        $movieService = $this->getMovieService();
        $pageSize = $moduleConfig->get('movie_detail_poster_page_size');
        
        $page = intval($page);
        if ( 0 >= $page ) {
            $page = 1;
        }
        
        $posters = $movieService->getPosters($id, ($page-1)*$pageSize, $pageSize);
        foreach ( $posters as $index => $poster ) {
            $posters[$index] = $poster->toArray();
            $posters[$index]['url'] = $movieService->getPosterUrlById($poster->id);
        }
        
        $pager = array();
        $pager['prev'] = (1 >= $page) ? false : $page-1;
        $pager['next'] = (($page)*$pageSize >= $movieService->countPosters($id)) ? false : $page+1;
        
        $isWatched = MovieService::MARK_WATCHED === $movieService->getMark($id);
        $name   = 'POSTERS_INDEX';
        $path   = $this->getParticleViewPath('Movie/Posters');
        $option = array();
        $data   = array(
            'posters'=>$posters, 
            'id'=>$id, 
            'pager'=>$pager, 
            'isWatched'=>$isWatched,
            'assetsURL' => X::system()->getConfiguration()->get('assets-base-url'),
        );
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->displayParticle($name);
    }
}