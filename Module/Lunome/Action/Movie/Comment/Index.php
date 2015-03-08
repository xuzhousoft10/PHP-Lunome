<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie\Comment;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Visual;

/**
 * The action class for movie/comment/index action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Index extends Visual { 
    /**
     * @param string $id
     * @param integer $page
     * @param string $scope
     */
    public function runAction( $id, $page=1, $scope='friends' ) {
        $movieService = $this->getMovieService();
        $account = $this->getUserService()->getAccount();
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('movie_detail_comment_page_size');
        
        if ( !$movieService->has($id) ) {
            return;
        }
        
        $page = intval($page);
        
        $position   = ($page-1)*$pageSize;
        if ( 'friends' === $scope ) {
            $comments = $movieService->getShortCommentsFromFriends($id, null, $position, $pageSize);
            $commentCount = $movieService->countShortCommentsFromFriends($id);
        } else {
            $comments = $movieService->getShortComments($id, null, $position, $pageSize);
            $commentCount = $movieService->countShortComments($id);
        }
        foreach ( $comments as $index => $comment ) {
            $comments[$index] = array();
            $comments[$index]['content'] = $comment->toArray();
            $comments[$index]['user'] = $account->getInformation($comment->commented_by)->toArray();
        }
        
        $pager = array();
        $pager['current'] = $page;
        $pager['pageCount'] = (0===($commentCount%$pageSize)) ? $commentCount/$pageSize : (intval($commentCount/$pageSize)+1);
        $pager['prev'] = (1 >= $page) ? false : $page-1;
        $pager['next'] = (($page)*$pageSize >= $commentCount) ? false : $page+1;
        
        $name   = 'COMMENTS_INDEX';
        $path   = $this->getParticleViewPath('Movie/Comments');
        $data   = array('comments'=>$comments, 'id'=>$id, 'pager'=>$pager, 'scope'=>$scope);
        $view = $this->getView()->getParticleViewManager()->load($name, $path);
        $view->getDataManager()->merge($data);
        $view->display();
    }
}