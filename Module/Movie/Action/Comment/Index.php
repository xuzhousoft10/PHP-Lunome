<?php
namespace X\Module\Movie\Action\Comment;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Lunome\Widget\Pager\Simple as SimplePager;
/**
 * 
 */
class Index extends Visual { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $id, $page=1, $scope='friends' ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movie = $movieService->get($id);
        if ( null === $movie ) {
            return $this->throw404();
        }
        
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('movie_detail_comment_page_size');
        $page = (0 >= (int)$page) ? 1 : (int)$page;
        $criteria = new Criteria();
        $criteria->position = ($page-1)*$pageSize;
        $criteria->limit = $pageSize;
        if ( 'friends' === $scope ) {
            $currentAccount = $this->getCurrentAccount();
            $friends = $currentAccount->getFriendManager()->find();
            foreach ( $friends as $index => $friend ) {
                $friends[$index] = $friend->getID();
            }
            $criteria->condition = array('commented_by'=>$friends);
        }
        
        $shortCommentManager = $movie->getShortCommentManager();
        $comments = $shortCommentManager->find($criteria);
        $commentCount = $shortCommentManager->count($criteria->condition);
        
        $pager = new SimplePager();
        $pager->setPagerURL($this->createURL('/',array('module'=>'movie','action'=>'comment/index','id'=>$id,'page'=>'{$page}', 'scope'=>$scope)));
        $pager->setCurrentPage($page);
        $pager->setPageSize($pageSize);
        $pager->setTotalNumber($commentCount);
        
        $name   = 'COMMENTS_INDEX';
        $path   = $this->getParticleViewPath('Comments');
        $data   = array('comments'=>$comments, 'id'=>$id, 'pager'=>$pager, 'scope'=>$scope);
        $view = $this->getView()->getParticleViewManager()->load($name, $path);
        $view->getDataManager()->merge($data);
        $view->display();
    }
}