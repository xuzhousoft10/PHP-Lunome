<?php
namespace X\Module\Movie\Action\Comment;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
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
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('movie_detail_comment_page_size');
        $movie = $movieService->get($id);
        if ( null === $movie ) {
            return;
        }
        
        $page = intval($page);
        $criteria = new Criteria();
        $criteria->position = ($page-1)*$pageSize;
        $criteria->limit = $pageSize;
        $shortCommentManager = $movie->getShortCommentManager();
        
        if ( 'friends' === $scope ) {
            $currentAccount = $this->getCurrentAccount();
            $friends = $currentAccount->getFriendManager()->find();
            foreach ( $friends as $index => $friend ) {
                $friends[$index] = $friend->getID();
            }
            $criteria->condition = array('commented_by'=>$friends);
        }
        
        $comments = $shortCommentManager->find($criteria);
        $commentCount = $shortCommentManager->count($criteria->condition);
        
        $pager = array();
        $pager['current'] = $page;
        $pager['pageCount'] = (0===($commentCount%$pageSize)) ? $commentCount/$pageSize : (intval($commentCount/$pageSize)+1);
        $pager['prev'] = (1 >= $page) ? false : $page-1;
        $pager['next'] = (($page)*$pageSize >= $commentCount) ? false : $page+1;
        
        $name   = 'COMMENTS_INDEX';
        $path   = $this->getParticleViewPath('Comments');
        $data   = array('comments'=>$comments, 'id'=>$id, 'pager'=>$pager, 'scope'=>$scope);
        $view = $this->getView()->getParticleViewManager()->load($name, $path);
        $view->getDataManager()->merge($data);
        $view->display();
    }
}