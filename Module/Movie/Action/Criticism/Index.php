<?php
namespace X\Module\Movie\Action\Criticism;
/**
 * 
 */
use X\Core\X;
use X\Module\Movie\Util\Action\MovieAttributeVisualAction;
use X\Module\Lunome\Module as LunomeModule;
/**
 * 
 */
class Index extends MovieAttributeVisualAction { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $movie ) {
        $criticismManager = $this->getMovie()->getCriticismManager();
        $criticisms = $criticismManager->find();
        
        $movie = $this->getMovie();
        $view = $this->getView();
        $view->setLayout($this->getLayoutViewPath('TwoColumnsBigLeft', LunomeModule::getModuleName()));
        $particleView = $view->getParticleViewManager()->load('CRITICISMS_INDEX', $this->getParticleViewPath('Criticism/Index'));
        $particleView->getDataManager()
            ->set('criticisms', $criticisms)
            ->set('movie', $movie)
            ->set('movieAccount', $this->getCurrentMovieAccount());
        
        $view->title = $movie->get('name').'的影评';
    }
}