<?php
namespace X\Module\Movie\Action\Poster;
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
    public function runAction( $movie, $poster ) {
        $movie = $this->getMovie();
        $poster = $movie->getPosterManager()->get($poster);
        if ( null === $poster ) {
            $this->throw404();
        }
        
        $view = $this->getView();
        $view->setLayout($this->getLayoutViewPath('TwoColumnsBigLeft', LunomeModule::getModuleName()));
        $particleView = $view->getParticleViewManager()->load('POSTER_DETAIL', $this->getParticleViewPath('Poster/Detail'));
        $particleView->getDataManager()
            ->set('poster', $poster)
            ->set('movie', $movie)
            ->set('movieAccount', $this->getCurrentMovieAccount())
            ->set('currentAccount', $this->getCurrentAccount());
        
        $view->title = $movie->get('name').'的图片';
    }
}