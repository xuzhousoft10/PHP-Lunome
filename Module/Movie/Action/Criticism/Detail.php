<?php
namespace X\Module\Movie\Action\Criticism;
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
    public function runAction( $movie, $criticism ) {
        $movie = $this->getMovie();
        $manager = $movie->getCriticismManager();
        
        $criticism = $manager->get($criticism);
        if ( null === $criticism ) {
            $this->throw404();
        }
        
        $view = $this->getView();
        $view->setLayout($this->getLayoutViewPath('TwoColumnsBigLeft', LunomeModule::getModuleName()));
        $particleView = $view->getParticleViewManager()->load('CRITICISM_DETAIL', $this->getParticleViewPath('Criticism/Detail'));
        $particleView->getDataManager()
            ->set('criticism', $criticism)
            ->set('movie', $movie)
            ->set('movieAccount', $this->getCurrentMovieAccount())
            ->set('currentAccount', $this->getCurrentAccount());
        
        $view->title = $movie->get('name').'的影评 -- '.$criticism->get('title');
    }
}