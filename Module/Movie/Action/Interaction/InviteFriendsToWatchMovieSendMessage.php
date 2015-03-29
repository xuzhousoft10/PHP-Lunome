<?php
namespace X\Module\Movie\Action\Interaction;
/**
 * 
 */
use X\Module\Lunome\Util\Action\JSON;
use X\Module\Movie\Service\Movie\Service as MovieService;
/**
 * 
 */
class InviteFriendsToWatchMovieSendMessage extends JSON {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $friends, $movie, $comment ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movieAccount = $movieService->getCurrentAccount();
        
        $friends = explode(',', $friends);
        if ( empty($friends) || empty($friends[0]) ) {
            return $this->error('Friend list can not be empty.');
        }
        
        $view = $this->getModule()->getPath('View/Particle/Interaction/NotificationGetMovieInvitation.php');
        foreach ( $friends as $friend ) {
            $movieAccount->sendWatchMovieInvitation($friend, $movie, $comment, $view);
        }
        return $this->success();
    }
}