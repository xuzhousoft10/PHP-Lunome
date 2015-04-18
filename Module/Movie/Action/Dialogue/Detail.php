<?php
namespace X\Module\Movie\Action\Dialogue;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Module as LunomeModule;
use X\Module\Movie\Util\Action\MovieAttributeVisualAction;
/**
 * 
 */
class Detail extends MovieAttributeVisualAction { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $movie, $dialogue ) {
        $movie = $this->getMovie();
        $dialogueManager = $movie->getClassicDialogueManager();
        
        $dialogue = $dialogueManager->get($dialogue);
        if ( null === $dialogue ) {
            $this->throw404();
        }
        
        $view = $this->getView();
        $view->setLayout($this->getLayoutViewPath('TwoColumnsBigLeft', LunomeModule::getModuleName()));
        $particleView = $view->getParticleViewManager()->load('DIALOGUE_DETAIL', $this->getParticleViewPath('Dialogue/Detail'));
        $particleView->getDataManager()
            ->set('dialogue', $dialogue)
            ->set('movie', $movie)
            ->set('movieAccount', $this->getCurrentMovieAccount())
            ->set('currentAccount', $this->getCurrentAccount());
        
        $view->title = $movie->get('name').'的经典台词 -- '.$dialogue->get('content');
    }
}