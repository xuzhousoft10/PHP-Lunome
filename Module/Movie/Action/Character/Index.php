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
class Index extends Visual { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $movie ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movie = $movieService->get($movie);
        if ( null === $movie ) {
            $this->throw404();
        }
        
        $characterManager = $movie->getCharacterManager();
        $characters = $characterManager->find();
        $movieAccount = $movieService->getCurrentAccount();
        
        $view = $this->getView();
        $view->setLayout($this->getLayoutViewPath('TwoColumnsBigLeft', LunomeModule::getModuleName()));
        $particleView = $view->getParticleViewManager()->load('CHARACTER_INDEX', $this->getParticleViewPath('Character/Index'));
        $particleView->getDataManager()
            ->set('characters', $characters)
            ->set('id', $movie->get('id'))
            ->set('movie', $movie)
            ->set('isWatched', $movieAccount->isWatched($movie->get('id')));
        
        $view->title = $movie->get('name').'的人物角色';
    }
}