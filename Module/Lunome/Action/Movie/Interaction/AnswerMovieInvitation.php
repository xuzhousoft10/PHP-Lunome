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
 * AnswerMovieInvitation
 * @author Michael Luthor <michaelluthor@163.com>
 */
class AnswerMovieInvitation extends Basic {
    /**
     * @param string $notification
     * @param integer $answer
     * @param string $comment
     */
    public function runAction( $notification, $answer, $comment ) {
        $userService = $this->getUserService();
        $answer = intval($answer);
        
        $notification = $userService->getNotification($notification);
        $view = 'Movie/Interaction/NotificationGetMovieInvitationAnswer';
        $this->getMovieService()->answerMovieInvitation($notification['sourceData']['id'], $answer, $comment, $view);
        $userService->closeNotification($notification['id']);
        echo json_encode(array('status'=>'ok'));
    }
}