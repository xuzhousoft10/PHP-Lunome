<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie\Interaction;

/**
 * use statements
 */
use X\Module\Lunome\Util\Action\Basic;

/**
 * InviteToWatchMovieSendMessage
 * @author Michael Luthor <michaelluthor@163.com>
 */
class InviteToWatchMovieSendMessage extends Basic {
    /**
     * @param string $friend
     * @param string $movie
     * @param string $comment
     */
    public function runAction( $friend, $movie, $comment ) {
        $userService = $this->getUserService();
        if ( !$userService->getAccount()->hasFriend($friend) ) {
            return;
        }
        
        $movieService = $this->getMovieService();
        if ( !$movieService->has($movie) ) {
            return;
        }
        
        $view = 'Movie/Interaction/NotificationGetMovieInvitation';
        $this->getMovieService()->sendMovieInvitation($friend, $movie, $comment, $view);
        echo json_encode(array('status'=>'ok'));
    }
}