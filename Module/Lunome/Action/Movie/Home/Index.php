<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie\Home;

/**
 * use statements
 */
use X\Module\Lunome\Util\Action\VisualUserHome;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * Index
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Index extends VisualUserHome {
    /**
     * @param string $id
     * @param integer $mark
     * @param integer $page
     */
    public function runAction( $id, $mark=null, $page=1 ) {
        $movieService = $this->getMovieService();
        $userService = $this->getUserService();
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('movie_user_home_page_size');
        
        if ( !$userService->getAccount()->has($id) ) {
            $this->throw404();
        }
        
        $mark = intval($mark);
        if ( MovieService::MARK_UNMARKED === $mark ) {
            $mark = MovieService::MARK_INTERESTED;
        }
        $marks = $movieService->getMarkNames();
        unset($marks[MovieService::MARK_UNMARKED]);
        $marks = array('data'=>$marks, 'actived'=>$mark);
        
        $page = intval($page);
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
        
        $this->homeUserAccountID = $id;
    }
}