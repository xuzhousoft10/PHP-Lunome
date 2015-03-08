<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie\Interaction;

/**
 * use statements
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Basic;
/**
 * InviteFriendsToWatchMovieSendMessage
 * @author Michael Luthor <michaelluthor@163.com>
 */
class InviteFriendsToWatchMovieSendMessage extends Basic {
    /**
     * @param array $friends
     * @param string $movie
     * @param string $comment
     */
    public function runAction( $friends, $movie, $comment ) {
        $accountManager = $this->getUserService()->getAccount();
        $movieService = $this->getMovieService();
        if ( !$movieService->has($movie) ) {
            echo json_encode(array('status'=>'err'));
            X::system()->stop();
        }
        
        $friends = explode(',', $friends);
        if ( empty($friends) ) {
            echo json_encode(array('status'=>'err'));
            X::system()->stop();
        }
        
        $view = 'Movie/Interaction/NotificationGetMovieInvitation';
        foreach ( $friends as $friend ) {
            if ( !$accountManager->hasFriend($friend) ) {
                continue;
            }
            $this->getMovieService()->sendMovieInvitation($friend, $movie, $comment, $view);
        }
        echo json_encode(array('status'=>'ok'));
    }
}