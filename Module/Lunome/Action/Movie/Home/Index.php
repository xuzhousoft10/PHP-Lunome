<?php
/**
 * 
 */
namespace X\Module\Lunome\Action\Movie\Home;

/**
 * 
 */
use X\Module\Lunome\Util\Action\VisualUserHome;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * 
 */
class Index extends VisualUserHome {
    /**
     * @param unknown $id
     */
    public function runAction( $id, $mark=null, $page=1 ) {
        $this->homeUserAccountID = $id;
        $movieService = $this->getMovieService();
        
        /* Setup marks. */
        $mark *= 1;
        if ( empty($mark) ) {
            $mark = MovieService::MARK_INTERESTED;
        }
        $marks = $movieService->getMarkNames();
        unset($marks[MovieService::MARK_UNMARKED]);
        $marks = array('data'=>$marks, 'actived'=>$mark);
        
        /* Setup movies. */
        $pageSize = 15;
        $page *= 1;
        $page = ( 0 >= $page ) ? 1 : $page;
        $position = ($page-1)*$pageSize;
        $movies = $movieService->getMarked($mark, array(), $pageSize, $position, $id);
        /* 填充封面信息和评分信息 */
        foreach ( $movies as $index => $movie ) {
            if ( 0 === $movie['has_cover']*1 ) {
                $movies[$index]['cover'] = $movieService->getMediaDefaultCoverURL();
            } else {
                $movies[$index]['cover'] = $movieService->getCoverURL($movie['id']);
            }
            if ( MovieService::MARK_WATCHED === $mark ) {
                $movies[$index]['score'] = $movieService->getRateScore($movie['id']);
            }
        }
        
        /* setup pager */
        $pager = array('prev'=>false, 'next'=>false);
        $count = $movieService->countMarked($mark, null, $id);
        $pager['total'] = (0===$count%$pageSize) ? ($count/$pageSize) : (intval($count/$pageSize)+1);
        $pager['current'] = $page;
        $pager['prev'] = (0 >= $page-1) ? false : $page-1;
        $pager['next'] = ($pager['total']<$page+1) ? false : $page+1;
        
        /* User home index */
        $name   = 'MOVIE_HOME_INDEX';
        $path   = $this->getParticleViewPath('Movie/HomeIndex');
        $option = array();
        $data   = array(
            'accountID' => $id, 
            'marks'     => $marks, 
            'movies'    => $movies, 
            'pager'     => $pager
        );
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}