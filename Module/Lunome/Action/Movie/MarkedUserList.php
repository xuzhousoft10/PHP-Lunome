<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * use statemnets
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;

/**
 * MarkedUserList
 * @author Michael Luthor <michaelluthor@163.com>
 */
class MarkedUserList extends Visual {
    /**
     * @param string $id
     * @param integer $mark
     * @param string $scope
     */
    public function runAction( $id, $mark, $scope, $page=1 ) {
        /* setup env. */
        $movieService = $this->getMovieService();
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('movie_detail_marked_user_list_page_size');
        $accountManager = $this->getUserService()->getAccount();
        
        /* check parameters. */
        $page = intval($page);
        $page = ( 1 > $page ) ? 1 : $page;
        if ( 'friends' === $scope ) {
            $accounts = $movieService->getMarkedFriendAccounts($id, $mark, ($page-1)*$pageSize, $pageSize);
            $count = $movieService->countMarkedUsers($id, $mark);
        } else {
            $accounts = $movieService->getMarkedAccounts($id, $mark, ($page-1)*$pageSize, $pageSize);
            $count = $movieService->countMarkedFriends($id, $mark);
        }
        
        /* setup pager. */
        $pager = array();
        $pager['current'] = $page;
        $pager['pageCount'] = (0===$count%$pageSize) ? $count/$pageSize : intval($count/$pageSize)+1;
        $pager['prev'] = ( 1 >= $page*1 ) ? false : $page-1;
        $pager['next'] = ( $count<=$pageSize || $page*$pageSize >= $count ) ? false : $page+1;
        
        /* setup view. */
        $view       = $this->getView();
        $viewName   = 'MARKED_USER_LIST';
        $path       = $this->getParticleViewPath('Movie/MarkedUserList');
        $listView   = $this->loadParticle($viewName, $path);
        
        /* add data to view. */
        $this->setDataToParticle($viewName, 'accounts', $accounts);
        $this->setDataToParticle($viewName, 'pager', $pager);
        $this->setDataToParticle($viewName, 'id', $id);
        $this->setDataToParticle($viewName, 'mark', $mark);
        $this->setDataToParticle($viewName, 'scope', $scope);
        $this->setDataToParticle($viewName, 'sexNames', $accountManager->getSexNames());
        $this->setDataToParticle($viewName, 'sexMarks', $accountManager->getSexMarks());
        $this->setDataToParticle($viewName, 'sexualityNames', $accountManager->getSexualityNames());
        $this->setDataToParticle($viewName, 'emotionStatuNames', $accountManager->getEmotionStatuNames());
        
        
        /* display particle view. */
        $listView->display();
    }
}