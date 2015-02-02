<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie\Interaction;

/**
 * use statements
 */
use X\Module\Lunome\Util\Action\FriendManagement;

/**
 * Friends
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Friends extends FriendManagement {
    /**
     * @param array $friends
     */
    public function runAction( $friends ) {
        $userService = $this->getUserService();
        $movieService = $this->getMovieService();
        
        $accounts = array();
        $accounts[] = $userService->getCurrentUserId();
        $accounts = array_merge($accounts, $friends);
        $movies = $movieService->getInterestedMovieSetByAccounts($accounts);
        $friends = $userService->getAccount()->getInformations($friends);
        
        /* 填充封面信息和评分信息 */
        foreach ( $movies as $index => $movie ) {
            $movies[$index] = $movie->toArray();
            if ( 0 === $movie->has_cover*1 ) {
                $movies[$index]['cover'] = $movieService->getMediaDefaultCoverURL();
            } else {
                $movies[$index]['cover'] = $movieService->getCoverURL($movie->id);
            }
        }
        
        $name   = 'USER_FRIENDS_INTERACTION';
        $path   = $this->getParticleViewPath('Movie/Interaction/InviteFriendsToWatchMovie');
        $option = array();
        $data   = array('movies'=>$movies, 'friends'=>$friends);
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        $this->getView()->title = '邀请好友一起去看电影';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\FriendManagement::getActiveSettingItem()
     */
    protected function getActiveSettingItem() {
        return self::FRIEND_MENU_ITEM_INTERACTION;
    }
}