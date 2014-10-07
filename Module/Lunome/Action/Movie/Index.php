<?php
/**
 * The action file for movie/index action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\VisualMainMediaList;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/index action.
 * @author Unknown
 */
class Index extends VisualMainMediaList { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction($mark='unmarked', $page=1) {
        /* Get unmarked movies */
        $pageSize = $this->getPageSize();
        $condition  = array();
        $length     = $pageSize;
        $position   = $pageSize * ($page-1);
        if ( 'unmarked' === $mark ) {
            $movies = $this->getMovieService()->getUnmarkedMovies($condition, $length, $position);
        } else {
            $movies = $this->getMovieService()->getMarkedMovies($mark, $length, $position);
        }
        
        /* setup pager data */
        $pager = array();
        $pager['current']   = $page;
        $pager['prev']      = ( 1 == $page ) ? 1 : $page-1;
        $pager['next']      = $page + 1;
        $pager['mark']      = $mark;
        
        /* Mark information */
        $markInfo = array();
        $markInfo['actived']    = $mark;
        $markInfo['unmarked']   = $this->getMovieService()->countUnmarked();
        $markInfo['interested'] = $this->getMovieService()->countMarked(MovieService::MARK_INTERESTED);
        $markInfo['watched']    = $this->getMovieService()->countMarked(MovieService::MARK_WATCHED);
        $markInfo['ignored']    = $this->getMovieService()->countMarked(MovieService::MARK_IGNORED);
        
        /* Load index view */
        $name   = 'MOVIE_INDEX';
        $path   = $this->getParticleViewPath('Movie/Index');
        $option = array(); 
        $data   = array('movies'=>$movies, 'pager'=>$pager, 'markInfo'=>$markInfo, 'mark'=>$mark);
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}