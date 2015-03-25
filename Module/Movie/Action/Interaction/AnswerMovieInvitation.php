<?php
namespace X\Module\Movie\Action\Interaction;
/**
 * 
 */
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Movie\Service\Movie\Service as MovieService;
/**
 * AnswerMovieInvitation
 * @author Michael Luthor <michaelluthor@163.com>
 */
class AnswerMovieInvitation extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $notification, $answer, $comment ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movieAccount = $movieService->getCurrentAccount();
        $answer = intval($answer);
        $view = $this->getModule()->getPath('View/Particle/Interaction/NotificationGetMovieInvitationAnswer.php');
        $movieAccount->answerWatchMovieInvitation($notification, $answer, $comment, $view);
        echo json_encode(array('status'=>'ok'));
    }
}