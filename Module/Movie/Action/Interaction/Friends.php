<?php
namespace X\Module\Movie\Action\Interaction;
/**
 * 
 */
use X\Module\Lunome\Util\Action\FriendManagement;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Account\Service\Account\Service as AccountService;
use X\Module\Movie\Service\Movie\Core\Model\MovieInvitationModel;

/**
 * Friends
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Friends extends FriendManagement {
    /**
     * @param array $friends
     */
    public function runAction( $friends ) {
        if ( empty($friends) || !is_array($friends) ) {
            $friends = array(0);
        }
        
        $moduleConfig = $this->getModule()->getConfiguration();
        $peopleCount = intval($moduleConfig->get('user_interaction_max_friend_count'));
        if ( count($friends) > $peopleCount ) {
            $friends = array_slice($friends, 0, $peopleCount);
        }
        
        $friends[] = $this->getCurrentAccount()->getID();
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movies = $movieService->getInterestedByAccounts($friends);
        
        array_pop($friends);
        /* @var $accountService AccountService */
        $accountService = $this->getService(AccountService::getServiceName());
        $friends = $accountService->find(array('id'=>$friends));
        $selectedFriendIDs = array();
        $selectedFriendNames = array();
        foreach ( $friends as $friend ) {
            $selectedFriendIDs[] = $friend->getID();
            $selectedFriendNames[] = $friend->getProfileManager()->get('nickname');
        }
        
        $view = $this->getView();
        $viewName   = 'USER_FRIENDS_INTERACTION';
        $path   = $this->getParticleViewPath('Interaction/InviteFriendsToWatchMovie');
        $this->loadParticle($viewName, $path);
        $this->setDataToParticle($viewName, 'movies', $movies);
        $this->setDataToParticle($viewName, 'selectedFriendIDs', implode(',', $selectedFriendIDs));
        $this->setDataToParticle($viewName, 'selectedFriendNames', implode(',', $selectedFriendNames));
        $commentLength = MovieInvitationModel::model()->getAttribute('comment')->getLength();
        $this->setDataToParticle($viewName, 'commentLength', $commentLength);
        
        $view->title = '邀请好友一起去看电影';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\FriendManagement::getActiveSettingItem()
     */
    protected function getActiveSettingItem() {
        return self::FRIEND_MENU_ITEM_INTERACTION;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        parent::beforeDisplay();
        $assetsURL = $this->getAssetsURL();
        $this->addScriptFile('invite', $assetsURL.'/js/movie/invite_friends.js');
    }
}