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

/**
 * The action class for user/friend/index action.
 * @author Unknown
 */
class Index extends FriendManagement { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $page=1 ) {
        $moduleConfig = $this->getModule()->getConfiguration();
        $accountManager = $this->getUserService()->getAccount();
        $pageSize = $moduleConfig->get('user_friend_index_page_size');
        $page = intval($page);
        
        $position = ($page-1)*$pageSize;
        $position = ( 0 > $position ) ? 0 : $position;
        $friends = $accountManager->getFriends($position, $pageSize);
        
        if ( !empty($friends) ) {
            /* @var $regionService RegionService */
            $regionService = X::system()->getServiceManager()->get(RegionService::getServiceName());
            foreach ( $friends as $index => $friend ) {
                $friends[$index] = $friend->toArray();
                $friends[$index]['living_country']    = $regionService->getNameByID($friend->living_country);
                $friends[$index]['living_province']   = $regionService->getNameByID($friend->living_province);
                $friends[$index]['living_city']       = $regionService->getNameByID($friend->living_city);
                $friends[$index]['sexSign']           = $accountManager->getSexMark($friend->sex);
                $friends[$index]['sex']               = $accountManager->getSexName($friend->sex);
                $friends[$index]['sexuality']         = $accountManager->getSexualityName($friend->sexuality);
                $friends[$index]['emotion_status']    = $accountManager->getEmotionStatuName($friend->emotion_status);
            }
        }
        
        $count = $accountManager->countFriends();
        $pager = array(
            'prev'      => ( 1 >= $page*1 ) ? false : $page-1,
            'next'      => ( $count<=$pageSize || ($page-1)*$pageSize >= $count ) ? false : $page+1,
            'current'   => $page,
            'pageCount' => ( 0===($count%$pageSize) ) ? $count/$pageSize : intval($count/$pageSize)+1,
        );
        
        /* Load friend index view. */
        $name   = 'FRIEND_INDEX';
        $path   = $this->getParticleViewPath('User/Friend/Index');
        $option = array();
        $data   = array('friends'=>$friends, 'pager'=>$pager);
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->title = '我的好友';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\FriendManagement::getActiveSettingItem()
     */
    protected function getActiveSettingItem() {
        return self::FRIEND_MENU_ITEM_LIST;
    }
}