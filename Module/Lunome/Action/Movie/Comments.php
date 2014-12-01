<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/ignore action.
 * @author Unknown
 */
class Comments extends Basic { 
    /**
     * @param unknown $id
     * @param unknown $content
     */
    public function runAction( $id, $page=1 ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $account = $this->getUserService()->getAccount();
        
        $length     = 5;
        $position   = ($page-1)*$length;
        $comments = $movieService->getShortComments($id, null, $position, $length);
        foreach ( $comments as $index => $comment ) {
            $comments[$index] = array();
            $comments[$index]['comment'] = $comment->toArray();
            $comments[$index]['user'] = $account->get($comment->commented_by)->toArray();
        }
        $count = $movieService->countShortComments($id);
        echo json_encode(array('list'=>$comments, 'count'=>$count));
    }
}