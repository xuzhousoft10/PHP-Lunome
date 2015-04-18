<?php
namespace X\Module\Movie\Action\Dialogue;
/**
 * 
 */
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Lunome\Module as LunomeModule;
/**
 * 
 */
class Index extends Visual { 
    /**
     * @param string $id
     * @param integer $page
     */
    public function runAction( $movie) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movie = $movieService->get($movie);
        if ( null === $movie ) {
            $this->throw404();
        }
        
        $movieID = $movie->get('id');
        $dialogueManager = $movie->getClassicDialogueManager();
        $dialogues = $dialogueManager->find();
        $movieAccount = $movieService->getCurrentAccount();
        
        $view = $this->getView();
        $view->setLayout($this->getLayoutViewPath('TwoColumnsBigLeft', LunomeModule::getModuleName()));
        $particleView = $view->getParticleViewManager()->load('PICTURE_INDEX', $this->getParticleViewPath('Dialogue/Index'));
        $particleView->getDataManager()
            ->set('dialogues', $dialogues)
            ->set('movie', $movie)
            ->set('isWatched', $movieAccount->isWatched($movieID));
        
        $view->title = $movie->get('name').'的经典台词';
    }
}