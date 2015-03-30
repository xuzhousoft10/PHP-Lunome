<?php
namespace X\Module\Account\Action\Friend;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\FriendManagement;
use X\Module\Account\Service\Account\Core\Model\AccountFriendshipRequestModel;
use X\Module\Account\Service\Account\Service as AccountService;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
/**
 * The action class for user/friend/index action.
 * @author Unknown
 */
class Search extends FriendManagement { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $condition=null, $page=1 ) {
        $currentAccountFriendManager = $this->getCurrentAccount()->getFriendManager();
        $moduleConfig = $this->getModule()->getConfiguration();
        $page = intval($page);
        $view = $this->getView();
        
        $informations = false;
        $pager = array('prev'=>false, 'next'=>false);
        if ( !empty($condition) && is_array($condition) ) {
            /* @var $accountService AccountService */
            $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
            $pageSize = $moduleConfig->get('user_friend_search_result_page_size');
            $filteredCondition = $this->filterConditions($condition);
            
            $criteria = new Criteria();
            $criteria->condition = $filteredCondition;
            $criteria->position = ($page-1)*$pageSize;
            $criteria->limit = $pageSize;
            $informations = $currentAccountFriendManager->findNonFriends($criteria);
            $resultCount = $currentAccountFriendManager->countNonFriends($filteredCondition);
            $pager = array(
                'prev'      => ( 1 >= $page*1 ) ? false : $page-1,
                'next'      => ( $resultCount<=$pageSize || ($page-1)*$pageSize >= $resultCount ) ? false : $page+1,
                'current'   => $page,
                'pageCount' => ( 0===($resultCount%$pageSize) ) ? $resultCount/$pageSize : intval($resultCount/$pageSize)+1,
            );
        }
        
        $name   = 'FRIEND_SEARCH';
        $path   = $this->getParticleViewPath('Friend/Search');
        $option = array();
        $data   = array('informations'=>$informations, 'condition'=>$condition, 'pager'=>$pager);
        $this->loadParticle($name, $path, $option, $data);
        $view->title = '寻找好友';
        
        $this->setDataToParticle($name, 'sexMap', $moduleConfig->get('account_profile_sex_names'));
        $this->setDataToParticle($name, 'sexualityMap', $moduleConfig->get('account_profile_sexuality'));
        $this->setDataToParticle($name, 'emotionMap', $moduleConfig->get('account_profile_emotion_status'));
        $length = AccountFriendshipRequestModel::model()->getAttribute('message')->getLength();
        $this->setDataToParticle($name, 'toBeFriendMessageLength', $length);
        $peopleLeft = $moduleConfig->get('user_friend_max_count')-$currentAccountFriendManager->count();
        $this->setDataToParticle($name, 'peopleLeft', $peopleLeft);
    }
    
    /**
     * @param unknown $conditions
     * @return multitype:
     */
    private function filterConditions( $conditions ) {
        $conditions = array_filter($conditions, array($this, 'filterConditionRemoveEmptyCondition'));
        return $conditions;
    }
    
    /**
     * @param unknown $item
     * @return boolean
     */
    public function filterConditionRemoveEmptyCondition( $item ) {
        return !empty($item);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\FriendManagement::getActiveSettingItem()
     */
    protected function getActiveSettingItem() {
        return self::FRIEND_MENU_ITEM_SEARCH;
    }
}