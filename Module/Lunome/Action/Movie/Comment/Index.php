<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie\Comment;

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
    public function runAction( $id, $page=1, $scope='friends' ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $account = $this->getUserService()->getAccount();
        
        $length     = 5;
        $position   = ($page-1)*$length;
        if ( 'friends' === $scope ) {
            $comments = $movieService->getShortCommentsFromFriends($id, null, $position, $length);
            $commentCount = $movieService->countShortCommentsFromFriends($id);
        } else {
            $comments = $movieService->getShortComments($id, null, $position, $length);
            $commentCount = $movieService->countShortComments($id);
        }
        foreach ( $comments as $index => $comment ) {
            $comments[$index] = array();
            $comments[$index]['content'] = $comment->toArray();
            $comments[$index]['user'] = $account->getInformation($comment->commented_by)->toArray();
        }
        
        $pager = array();
        $pager['current'] = $page;
        $pager['pageCount'] = (0===($commentCount%$length)) ? $commentCount/$length : (intval($commentCount/$length)+1);
        $pager['prev'] = (1 >= $page) ? false : $page-1;
        $pager['next'] = (($page)*$length >= $commentCount) ? false : $page+1;
        
        $name   = 'COMMENTS_INDEX';
        $path   = $this->getParticleViewPath('Movie/Comments');
        $option = array();
        $data   = array('comments'=>$comments, 'id'=>$id, 'pager'=>$pager, 'scope'=>$scope);
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->displayParticle($name);
    }
}