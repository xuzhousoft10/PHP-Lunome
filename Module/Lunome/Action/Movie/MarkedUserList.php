<?php
/**
 * 
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * 
 */
use X\Module\Lunome\Util\Action\Visual;

/**
 * 
 */
class MarkedUserList extends Visual {
    /**
     * @param unknown $id
     * @param unknown $mark
     * @param unknown $scope
     */
    public function runAction( $id, $mark, $scope, $page=1 ) {
        $movieService = $this->getMovieService();
        
        $page *= 1;
        $page = ( 1 > $page ) ? 1 : $page;
        $pageSize = 10;
        
        if ( 'friends' === $scope ) {
            $accounts = $movieService->getMarkedFriendAccounts($id, $mark, ($page-1)*$pageSize, $pageSize);
            $count = $movieService->countMarkedUsers($id, $mark);
        } else {
            $accounts = $movieService->getMarkedAccounts($id, $mark, ($page-1)*$pageSize, $pageSize);
            $count = $movieService->countMarkedFriends($id, $mark);
        }
        
        $pager = array();
        $pager['current'] = $page;
        $pager['pageCount'] = (0===$count%$pageSize) ? $count/$pageSize : intval($count/$pageSize)+1;
        $pager['prev'] = ( 1 >= $page*1 ) ? false : $page-1;
        $pager['next'] = ( $count<=$pageSize || $page*$pageSize >= $count ) ? false : $page+1;
        
        $name   = 'MARKED_USER_LIST';
        $path   = $this->getParticleViewPath('Movie/MarkedUserList');
        $option = array();
        $data   = array(
            'accounts'  => $accounts, 
            'pager'     => $pager,
            'id'        => $id,
            'mark'      => $mark,
            'scope'     => $scope,
        );
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->displayParticle($name);
    }
}