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
use X\Library\XMath\Number;

/**
 * The action class for movie/index action.
 * @author Unknown
 */
class Index extends VisualMainMediaList { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction($mark=MovieService::MARK_NAME_UNMARKED, $page=1) {
        /* Get unmarked movies */
        $pageSize = $this->getPageSize();
        $condition  = array();
        $length     = $pageSize;
        $position   = $pageSize * ($page-1);
        if ( MovieService::MARK_NAME_UNMARKED === $mark ) {
            $movies = $this->getMovieService()->getUnmarkedMovies($condition, $length, $position);
        } else {
            $movies = $this->getMovieService()->getMarkedMovies($mark, $length, $position);
        }
        
        /* Mark information */
        $markInfo = array();
        $markInfo['actived']    = $mark;
        $markInfo['unmarked']   = $this->getMovieService()->countUnmarked();
        $markInfo['interested'] = $this->getMovieService()->countMarked(MovieService::MARK_INTERESTED);
        $markInfo['watched']    = $this->getMovieService()->countMarked(MovieService::MARK_WATCHED);
        $markInfo['ignored']    = $this->getMovieService()->countMarked(MovieService::MARK_IGNORED);
        
        /* setup pager data */
        $movieCount = $markInfo[$mark];
        $pager = array();
        $pager['current']   = $page;
        $pager['total']     = (0===$movieCount%$pageSize) ? $movieCount/$pageSize : intval($movieCount/$pageSize)+1;
        $pager['canPrev']   = ( 1 < $page );
        $pager['canNext']   = ( $pager['total'] > $page );
        $pager['prev']      = $pager['canPrev'] ? $page-1 : 1;
        $pager['next']      = $pager['canNext'] ? $page + 1 : $pager['total'];
        $pager['mark']      = $mark;
        $pager['items']     = Number::getRound($page, $this->getPageItemCount(), 1, $pager['total']);
        
        /* Load index view */
        $name   = 'MOVIE_INDEX';
        $path   = $this->getParticleViewPath('Movie/Index');
        $option = array(); 
        $data   = array('movies'=>$movies, 'pager'=>$pager, 'markInfo'=>$markInfo, 'mark'=>$mark);
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}