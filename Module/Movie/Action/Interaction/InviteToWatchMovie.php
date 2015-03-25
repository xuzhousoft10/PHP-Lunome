<?php
namespace X\Module\Movie\Action\Interaction;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Userinteraction;
use X\Module\Movie\Service\Movie\Core\Model\MovieInvitationModel;
use X\Module\Account\Service\Account\Service as AccountService;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
/**
 * InviteToWatchMovie
 * @author Michael Luthor <michaelluthor@163.com>
 */
class InviteToWatchMovie extends Userinteraction {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $id ) {
        /* @var $accountService AccountService */
        $accountService = $this->getService(AccountService::getServiceName());
        $currentAccount = $this->getCurrentAccount();
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        
        $criteria = new Criteria();
        $criteria->limit = 10;
        $accounts = array($currentAccount->getID(), $id);
        $movies = $movieService->getInterestedByAccounts($accounts, $criteria);
        
        $view = $this->getView();
        $viewName = 'MOVIE_INTERACTION_INVITETOWATCHMOVIE';
        $path   = $this->getParticleViewPath('Interaction/InviteToWatchMovie');
        $this->loadParticle($viewName, $path);
        $this->setDataToParticle($viewName, 'movies', $movies);
        $this->setDataToParticle($viewName, 'friendInformation', $accountService->get($id));
        $commentLength = MovieInvitationModel::model()->getAttribute('comment')->getLength();
        $this->setDataToParticle($viewName, 'commentLength', $commentLength);
        
        $view->title = '想请TA看场电影';
        $this->setActiveInteractionMenuItem(self::INTERACTION_MENU_ITEM_INVITE_TO_WATCH_MOVIE);
        $this->interactionMenuParams = array('id'=>$id);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        parent::beforeDisplay();
        $assetsURL = $this->getAssetsURL();
        $this->addScriptFile('invite', $assetsURL.'/js/movie/invite.js');
    }
}