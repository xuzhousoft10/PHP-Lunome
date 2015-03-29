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
use X\Module\Lunome\Widget\Pager\Simple as SimplePager;
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
        $movieService = $this->getService(MovieService::getServiceName());
        $movie = $movieService->get($id);
        if ( null === $movie ) {
            $this->throw404();
        }
        
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = 1;//$moduleConfig->get('movie_detail_marked_user_list_page_size');
        
        $page = ( 1 > (int)$page ) ? 1 : (int)$page;
        $criteria = new Criteria();
        $criteria->condition = array('movie_id'=>$id, 'mark'=>$mark);
        $criteria->position = ($page-1)*$pageSize;
        $criteria->limit = $pageSize;
        if ( 'friends' === $scope ) {
            $movieAccount = $movieService->getCurrentAccount();
            $accounts = $movieAccount->findMarkedFriends($criteria);
            $count = $movieAccount->countMarkedFriends($id, $mark);
        } else {
            $accounts = $movie->findMarkedAccounts($criteria);
            $count = $movie->countMarked($mark);
        }
        
        $pager = new SimplePager();
        $pager->setCurrentPage($page);
        $pager->setTotalNumber($count);
        $pager->setPageSize($pageSize);
        $pager->setPagerURL($this->createURL('/?module=movie&action=markedUserList', array('id'=>$id, 'mark'=>$mark, 'scope'=>$scope, 'page'=>'{$page}')));
        
        /* setup view. */
        $view       = $this->getView();
        $viewName   = 'MARKED_USER_LIST';
        $path       = $this->getParticleViewPath('MarkedUserList');
        $listView   = $this->loadParticle($viewName, $path);
        
        $accountModuel = $this->getModule(AccountModule::getModuleName());
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