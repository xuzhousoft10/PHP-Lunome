<?php
namespace X\Module\Movie\Action\Interaction;
/**
 * use statements
 */
use X\Module\Lunome\Util\Action\Userinteraction;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 * FindTopic
 * @author Michael Luthor <michaelluthor@163.com>
 */
class FindTopic extends Userinteraction {
    /**
     * @var MovieService
     */
    private $movieService = null;
    
    /**
     * @param string $id
     */
    public function runAction( $id ) {
        $moduleConfig = $this->getModule()->getConfiguration();
        $this->movieService = $this->getService(MovieService::getServiceName());
        
        $currentAccount = $this->getCurrentAccount();
        $accounts = array($currentAccount->getID(), $id);
        $criteria = new Criteria();
        $criteria->limit = (int)$moduleConfig->get('movie_topic_suggested_size');
        $likedMovies = $this->movieService->getLikedByAccounts($accounts, $criteria);
        $dislikedMovies = $this->movieService->getDislikedByAccounts($accounts, $criteria);
        
        /* @var $accountService AccountService */
        $accountService = $this->getService(AccountService::getServiceName());
        $name   = 'MOVIE_INTERACTION_FIND_TOPIC';
        $path   = $this->getParticleViewPath('Interaction/FindTopic');
        $data   = array(
            'movies'=>array('liked'=>$likedMovies, 'disliked'=>$dislikedMovies), 
            'friendInformation'=>$accountService->get($id));
        $this->loadParticle($name, $path)->getDataManager()->merge($data);
        $this->getView()->title = '想与TA聊聊电影';
        
        $this->setActiveInteractionMenuItem(self::INTERACTION_MENU_ITEM_GET_TOPIC);
        $this->interactionMenuParams = array('id'=>$id);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        parent::beforeDisplay();
        $assetsURL = $this->getAssetsURL();
        $this->addScriptFile('user-home-movie-index', $assetsURL.'/js/movie/topic.js');
    }
}