<?php
namespace X\Module\Movie\Action\Interaction;
/**
 * 
 */
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Lunome\Util\Action\JSON;
/**
 * 
 */
class AnswerMovieInvitation extends JSON {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $notification, $answer, $comment ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movieAccount = $movieService->getCurrentAccount();
        
        $answer = (int)$answer;
        $view = $this->getModule()->getPath('View/Particle/Interaction/NotificationGetMovieInvitationAnswer.php');
        $movieAccount->answerWatchMovieInvitation($notification, $answer, $comment, $view);
        return $this->success();
    }
}