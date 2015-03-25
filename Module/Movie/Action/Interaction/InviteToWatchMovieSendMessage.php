<?php
namespace X\Module\Movie\Action\Interaction;
/**
 * 
 */
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Lunome\Util\Action\Basic;
/**
 * InviteToWatchMovieSendMessage
 * @author Michael Luthor <michaelluthor@163.com>
 */
class InviteToWatchMovieSendMessage extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $friend, $movie, $comment ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movieAccount = $movieService->getCurrentAccount();
        $view = $this->getModule()->getPath('View/Particle/Interaction/NotificationGetMovieInvitation.php');
        $movieAccount->sendWatchMovieInvitation($friend, $movie, $comment, $view);
        echo json_encode(array('status'=>'ok'));
    }
}