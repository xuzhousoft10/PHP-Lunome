<?php
namespace X\Module\Account\Action\Friend;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\FriendManagement;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Account\Service\Account\Service as AccountService;
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
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        $moduleConfig = $this->getModule()->getConfiguration();
        $account = $this->getCurrentAccount();
        $pageSize = $moduleConfig->get('user_friend_index_page_size');
        $page = intval($page);
        
        $position = ($page-1)*$pageSize;
        $position = ( 0 > $position ) ? 0 : $position;
        $criteria = new Criteria();
        $criteria->position = $position;
        $criteria->limit = (int)$pageSize;
        $friends = $account->getFriendManager()->find($criteria);
        
        $count = $account->getFriendManager()->count($criteria);
        $pager = array(
            'prev'      => ( 1 >= $page*1 ) ? false : $page-1,
            'next'      => ( $count<=$pageSize || ($page-1)*$pageSize >= $count ) ? false : $page+1,
            'current'   => $page,
            'pageCount' => ( 0===($count%$pageSize) ) ? $count/$pageSize : intval($count/$pageSize)+1,
        );
        
        /* Load friend index view. */
        $name   = 'FRIEND_INDEX';
        $path   = $this->getParticleViewPath('Friend/Index');
        $data   = array('friends'=>$friends, 'pager'=>$pager);
        $indexView = $this->getView()->getParticleViewManager()->load($name, $path);
        $indexView->getDataManager()->merge($data);
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