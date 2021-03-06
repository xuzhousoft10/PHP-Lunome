<?php
namespace X\Module\Movie\Action\Interaction;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Userinteraction;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Movie\Service\Movie\Core\Instance\Invitation;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 * 
 */
class InviteToWatchMovie extends Userinteraction {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $id ) {
        $currentAccount = $this->getCurrentAccount();
        $criteria = new Criteria();
        $criteria->limit = 10;
        $accounts = array($currentAccount->getID(), $id);
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movies = $movieService->getInterestedByAccounts($accounts, $criteria);
        
        /* @var $accountService AccountService */
        $accountService = $this->getService(AccountService::getServiceName());
        
        $view = $this->getView();
        $viewName = 'MOVIE_INTERACTION_INVITETOWATCHMOVIE';
        $path   = $this->getParticleViewPath('Interaction/InviteToWatchMovie');
        $this->loadParticle($viewName, $path);
        $this->setDataToParticle($viewName, 'movies', $movies);
        $this->setDataToParticle($viewName, 'friendInformation', $accountService->get($id));
        $commentLength = Invitation::getMeta()->getAttribute('comment')->getLength();
        $this->setDataToParticle($viewName, 'commentLength', $commentLength);
        
        $view->title = '想请TA看场电影';
        $this->setActiveInteractionMenuItem(self::INTERACTION_MENU_ITEM_INVITE_TO_WATCH_MOVIE);
        $this->interactionMenuParams = array('id'=>$id);
    }
}