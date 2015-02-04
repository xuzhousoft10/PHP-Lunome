<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie\Interaction;

/**
 * use statements
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\FriendManagement;
use X\Module\Lunome\Model\Movie\MovieInvitationModel;

/**
 * Friends
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Friends extends FriendManagement {
    /**
     * @param array $friends
     */
    public function runAction( $friends ) {
        $moduleConfig = $this->getModule()->getConfiguration();
        $userService = $this->getUserService();
        $movieService = $this->getMovieService();
        $view = $this->getView();
        
        if ( empty($friends) || !is_array($friends) ) {
            $this->goBack();
        }
        
        $peopleCount = intval($moduleConfig->get('user_interaction_max_friend_count'));
        if ( count($friends) > $peopleCount ) {
            $this->goBack();
        }
        
        $accounts = array();
        $accounts[] = $userService->getCurrentUserId();
        $accounts = array_merge($accounts, $friends);
        $movies = $movieService->getInterestedMovieSetByAccounts($accounts);
        $friends = $userService->getAccount()->getInformations($friends);
        $selectedFriendIDs = array();
        $selectedFriendNames = array();
        foreach ( $friends as $friend ) {
            $selectedFriendIDs[] = $friend->account_id;
            $selectedFriendNames[] = $friend->nickname;
        }
        
        /* 填充封面信息和评分信息 */
        foreach ( $movies as $index => $movie ) {
            $movies[$index] = $movie->toArray();
            if ( 0 === $movie->has_cover*1 ) {
                $movies[$index]['cover'] = $movieService->getMediaDefaultCoverURL();
            } else {
                $movies[$index]['cover'] = $movieService->getCoverURL($movie->id);
            }
        }
        
        $viewName   = 'USER_FRIENDS_INTERACTION';
        $path   = $this->getParticleViewPath('Movie/Interaction/InviteFriendsToWatchMovie');
        $view->loadParticle($viewName, $path);
        $view->setDataToParticle($viewName, 'movies', $movies);
        $view->setDataToParticle($viewName, 'selectedFriendIDs', implode(',', $selectedFriendIDs));
        $view->setDataToParticle($viewName, 'selectedFriendNames', implode(',', $selectedFriendNames));
        $commentLength = MovieInvitationModel::model()->getAttribute('comment')->getLength();
        $view->setDataToParticle($viewName, 'commentLength', $commentLength);
        
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
        $assetsURL = $this->getAssetsURL();
        $this->getView()->addScriptFile('invite', $assetsURL.'/js/movie/invite_friends.js');
    }
}