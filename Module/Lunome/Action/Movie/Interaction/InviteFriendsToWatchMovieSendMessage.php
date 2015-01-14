<?php
/**
 * 
 */
namespace X\Module\Lunome\Action\Movie\Interaction;

/**
 * 
 */
use X\Module\Lunome\Util\Action\Basic;

/**
 * 
 */
class InviteFriendsToWatchMovieSendMessage extends Basic {
    /**
     * @param unknown $id
     */
    public function runAction( $friends, $movie, $comment ) {
        $friends = explode(',', $friends);
        $view = 'Movie/Interaction/NotificationGetMovieInvitation';
        foreach ( $friends as $friend ) {
            $this->getMovieService()->sendMovieInvitation($friend, $movie, $comment, $view);
        }
        echo json_encode(array('status'=>'ok'));
    }
}