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
class InviteToWatchMovieSendMessage extends Basic {
    /**
     * @param unknown $id
     */
    public function runAction( $friend, $movie, $comment ) {
        $view = 'Movie/Interaction/NotificationGetMovieInvitation';
        $this->getMovieService()->sendMovieInvitation($friend, $movie, $comment, $view);
        echo json_encode(array('status'=>'ok'));
    }
}