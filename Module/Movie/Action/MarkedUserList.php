<?php
namespace X\Module\Movie\Action;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Account\Module as AccountModule;
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
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
        $movieAccount = $movieService->getCurrentAccount();
        $movie = $movieService->get($id);
        if ( null === $movie ) {
            $this->throw404();
        }
        
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('movie_detail_marked_user_list_page_size');
        
        /* check parameters. */
        $page = intval($page);
        $page = ( 1 > $page ) ? 1 : $page;
        $criteria = new Criteria();
        $criteria->condition = array('movie_id'=>$id, 'mark'=>$mark);
        $criteria->position = ($page-1)*$pageSize;
        $criteria->limit = $pageSize;
        if ( 'friends' === $scope ) {
            $accounts = $movieAccount->findMarkedFriends($criteria);
            $count = $movieAccount->countMarkedFriends($id, $mark);
        } else {
            $accounts = $movie->findMarkedAccounts($criteria);
            $count = $movie->countMarked($mark);
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
        $path       = $this->getParticleViewPath('MarkedUserList');
        $listView   = $this->loadParticle($viewName, $path);
        
        $accountModuel = X::system()->getModuleManager()->get(AccountModule::getModuleName());
        $accountModuelConfiguration = $accountModuel->getConfiguration();
        /* add data to view. */
        $this->setDataToParticle($viewName, 'accounts', $accounts);
        $this->setDataToParticle($viewName, 'pager', $pager);
        $this->setDataToParticle($viewName, 'id', $id);
        $this->setDataToParticle($viewName, 'mark', $mark);
        $this->setDataToParticle($viewName, 'scope', $scope);
        $this->setDataToParticle($viewName, 'sexNames', $accountModuelConfiguration->get('account_profile_sex_names'));
        $this->setDataToParticle($viewName, 'sexMarks', $accountModuelConfiguration->get('account_profile_sex_signs'));
        $this->setDataToParticle($viewName, 'sexualityNames', $accountModuelConfiguration->get('account_profile_sexuality'));
        $this->setDataToParticle($viewName, 'emotionStatuNames', $accountModuelConfiguration->get('account_profile_emotion_status'));
        
        /* display particle view. */
        $listView->display();
    }
}