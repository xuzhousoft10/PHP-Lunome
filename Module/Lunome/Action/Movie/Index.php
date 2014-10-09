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
    public function runAction($mark=MovieService::MARK_NAME_UNMARKED, $page=1) {
        $this->activeMenuItem(self::MENU_ITEM_MOVIE);
        
        /* Get unmarked movies */
        $pageSize = $this->getPageSize();
        $condition  = array();
        $length     = $pageSize;
        $position   = $pageSize * ($page-1);
        if ( MovieService::MARK_NAME_UNMARKED === $mark ) {
            $movies = $this->getMovieService()->getUnmarked($condition, $length, $position);
        } else {
            $movies = $this->getMovieService()->getMarked($mark, $length, $position);
        }
        
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
        $data   = array('movies'=>$movies, 'markInfo'=>$markInfo, 'mark'=>$mark);
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        /* setup pager data */
        $movieCount = $markInfo[$mark];
        $this->setPager('MOVIE_INDEX', $page, $movieCount, array('mark'=>$mark));
    }
}