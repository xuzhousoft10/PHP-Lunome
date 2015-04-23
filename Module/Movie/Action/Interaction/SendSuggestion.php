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
class SendSuggestion extends JSON {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $friends, $movie, $comment='' ) {
        $this->checkLoginRequirement();
        
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movieAccount = $movieService->getCurrentAccount();
        
        if ( empty($friends) ) {
            return $this->error('Friend list can not be empty.');
        }
        
        $view = $this->getModule()->getPath('View/Particle/Interaction/NotificationMovieSuggestion.php');
        foreach ( $friends as $friend ) {
            $movieAccount->sendSuggestion($friend, $movie, $comment, $view);
        }
        return $this->success();
    }
}