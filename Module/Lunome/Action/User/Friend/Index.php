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
        $accountManager = $this->getUserService()->getAccount();
        
        $length = 10;
        $position = ($page-1)*$length;
        $position = ( 0 > $position ) ? 0 : $position;
        $friends = $accountManager->getFriends($position, $length);
        
        if ( !empty($friends) ) {
            /* @var $regionService RegionService */
            $regionService = X::system()->getServiceManager()->get(RegionService::getServiceName());
            foreach ( $friends as $index => $friend ) {
                $friends[$index]->living_country    = $regionService->getNameByID($friend->living_country);
                $friends[$index]->living_province   = $regionService->getNameByID($friend->living_province);
                $friends[$index]->living_city       = $regionService->getNameByID($friend->living_city);
            }
        }
        
        $count = $accountManager->countFriends();
        $pager = array(
            'prev'      => ( 1 >= $page*1 ) ? false : $page-1,
            'next'      => ( ($page-1)*$length >= $count ) ? false : $page+1,
            'current'   => $page,
            'pageCount' => ( 0===($count%$length) ) ? $count/$length : intval($count/$length)+1,
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