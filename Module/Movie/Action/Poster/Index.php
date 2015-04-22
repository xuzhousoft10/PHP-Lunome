<?php
namespace X\Module\Movie\Action\Poster;
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
        $posters = $this->getMovie()->getPosterManager()->find();
        
        $movie = $this->getMovie();
        $view = $this->getView();
        $view->setLayout($this->getLayoutViewPath('TwoColumnsBigLeft', LunomeModule::getModuleName()));
        $particleView = $view->getParticleViewManager()->load('POSTER_INDEX', $this->getParticleViewPath('Poster/Index'));
        $particleView->getDataManager()
            ->set('posters', $posters)
            ->set('movie', $movie)
            ->set('movieAccount', $this->getCurrentMovieAccount());
        
        $view->title = $movie->get('name').'的图片';
    }
}