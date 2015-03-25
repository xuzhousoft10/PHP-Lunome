<?php
namespace X\Module\Movie\Action\Interaction;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Movie\Service\Movie\Service as MovieService;
/**
 * InviteFriendsToWatchMovieSendMessage
 * @author Michael Luthor <michaelluthor@163.com>
 */
class InviteFriendsToWatchMovieSendMessage extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $friends, $movie, $comment ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movieAccount = $movieService->getCurrentAccount();
        
        $friends = explode(',', $friends);
        if ( empty($friends) ) {
            echo json_encode(array('status'=>'err'));
            X::system()->stop();
        }
        
        $view = $this->getModule()->getPath('View/Particle/Interaction/NotificationGetMovieInvitation.php');
        foreach ( $friends as $friend ) {
            $movieAccount->sendWatchMovieInvitation($friend, $movie, $comment, $view);
        }
        echo json_encode(array('status'=>'ok'));
    }
}