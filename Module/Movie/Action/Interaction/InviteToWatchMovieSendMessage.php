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
class InviteToWatchMovieSendMessage extends JSON {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $friend, $movie, $comment ) {
        $currentAccount = $this->getCurrentAccount();
        if ( !$currentAccount->getFriendManager()->isFriendWith($friend) ) {
            return $this->error('Unable to invite user which is not your friend.');
        }
        
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        if ( !$movieService->has($movie) ) {
            return $this->error('Movie does not exists.');
        }
        
        $movieAccount = $movieService->getCurrentAccount();
        $view = $this->getModule()->getPath('View/Particle/Interaction/NotificationGetMovieInvitation.php');
        $movieAccount->sendWatchMovieInvitation($friend, $movie, $comment, $view);
        return $this->success();
    }
}