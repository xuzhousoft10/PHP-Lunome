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
class AnswerMovieInvitation extends Basic {
    /**
     * @param unknown $id
     */
    public function runAction( $notification, $answer, $comment ) {
        $userService = $this->getUserService();
        
        $notification = $userService->getNotification($notification);
        $view = 'Movie/Interaction/NotificationGetMovieInvitationAnswer';
        $this->getMovieService()->answerMovieInvitation($notification['sourceData']['id'], $answer, $comment, $view);
        $userService->closeNotification($notification['id']);
        echo json_encode(array('status'=>'ok'));
    }
}