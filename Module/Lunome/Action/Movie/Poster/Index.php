<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie\Poster;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/ignore action.
 * @author Unknown
 */
class Index extends Visual { 
    /**
     * @param unknown $id
     * @param unknown $content
     */
    public function runAction( $id, $page=1 ) {
        if ( 0 >= $page*1 ) {
            $page = 1;
        }
        
        $pageSize = 12;
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
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
        $data   = array('posters'=>$posters, 'id'=>$id, 'pager'=>$pager, 'isWatched'=>$isWatched);
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->displayParticle($name);
    }
}