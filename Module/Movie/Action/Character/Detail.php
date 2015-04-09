<?php
namespace X\Module\Movie\Action\Character;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Lunome\Module as LunomeModule;
use X\Module\Movie\Service\Movie\Service as MovieService;
/**
 * 
 */
class Detail extends Visual { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $movie, $character ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movie = $movieService->get($movie);
        if ( null === $movie ) {
            $this->throw404();
        }
        
        $characterManager = $movie->getCharacterManager();
        $character = $characterManager->get($character);
        if ( null === $character ) {
            $this->throw404();
        }
        
        $movieAccount = $movieService->getCurrentAccount();
        
        $view = $this->getView();
        $view->setLayout($this->getLayoutViewPath('TwoColumnsBigLeft', LunomeModule::getModuleName()));
        $particleView = $view->getParticleViewManager()->load('CHARACTER_INDEX', $this->getParticleViewPath('Character/Detail'));
        $particleView->getDataManager()
            ->set('character', $character)
            ->set('movie', $movie)
            ->set('movieAccount', $movieAccount)
            ->set('currentAccount', $this->getCurrentAccount());
        
        $view->title = $movie->get('name').'的人物角色 -- '.$character->get('name');
    }
}