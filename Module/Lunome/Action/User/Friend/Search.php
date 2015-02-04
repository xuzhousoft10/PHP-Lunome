<?php
/**
 * The action file for user/friend/index action.
 */
namespace X\Module\Lunome\Action\User\Friend;

/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\FriendManagement;
use X\Module\Lunome\Service\Region\Service as RegionService;
use X\Module\Lunome\Model\Account\AccountFriendshipRequestModel;

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
        $accountManager = $this->getUserService()->getAccount();
        $moduleConfig = $this->getModule()->getConfiguration();
        $page = intval($page);
        $view = $this->getView();
        
        $informations = false;
        $pager = array('prev'=>false, 'next'=>false);
        if ( !empty($condition) && is_array($condition) ) {
            /* @var $regionService RegionService */
            $regionService = X::system()->getServiceManager()->get(RegionService::getServiceName());
            
            $pageSize = $moduleConfig->get('user_friend_search_result_page_size');
            $filteredCondition = $this->filterConditions($condition);
            $result = $accountManager->findFriends($filteredCondition, ($page-1)*$pageSize, $pageSize);
            foreach ( $result['data'] as $index => $information ) {
                $informations[$index] = $information->toArray();
                $informations[$index]['living_country']     = $regionService->getNameByID($information->living_country);
                $informations[$index]['living_province']    = $regionService->getNameByID($information->living_province);
                $informations[$index]['living_city']        = $regionService->getNameByID($information->living_city);
                $informations[$index]['sexSign']                 = $accountManager->getSexMark($information->sex);
                $informations[$index]['sex']                     = $accountManager->getSexName($information->sex);
                $informations[$index]['sexuality']               = $accountManager->getSexualityName($information->sexuality);
                $informations[$index]['emotion_status']          = $accountManager->getEmotionStatuName($information->emotion_status);
            }
            
            $pager = array(
                'prev'      => ( 1 >= $page*1 ) ? false : $page-1,
                'next'      => ( $result['count']<=$pageSize || ($page-1)*$pageSize >= $result['count'] ) ? false : $page+1,
                'current'   => $page,
                'pageCount' => ( 0===($result['count']%$pageSize) ) ? $result['count']/$pageSize : intval($result['count']/$pageSize)+1,
            );
        }
        
        $name   = 'FRIEND_SEARCH';
        $path   = $this->getParticleViewPath('User/Friend/Search');
        $option = array();
        $data   = array('informations'=>$informations, 'condition'=>$condition, 'pager'=>$pager);
        $view->loadParticle($name, $path, $option, $data);
        $view->title = '寻找好友';
        
        $view->setDataToParticle($name, 'sexMap', $accountManager->getSexNames());
        $view->setDataToParticle($name, 'sexualityMap', $accountManager->getSexualityNames());
        $view->setDataToParticle($name, 'emotionMap', $accountManager->getEmotionStatuNames());
        $length = AccountFriendshipRequestModel::model()->getAttribute('message')->getLength();
        $view->setDataToParticle($name, 'toBeFriendMessageLength', $length);
        $peopleLeft = $moduleConfig->get('user_friend_max_count')-$accountManager->countFriends();
        $view->setDataToParticle($name, 'peopleLeft', $peopleLeft);
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
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        $assetsURL = $this->getAssetsURL();
        $this->getView()->addScriptFile('User-Setting-Information', $assetsURL.'/js/user/search.js');
    }
}