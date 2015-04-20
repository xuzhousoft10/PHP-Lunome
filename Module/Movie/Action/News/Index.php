<?php
namespace X\Module\Movie\Action\News;
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
        $newsManager = $this->getMovie()->getNewsManager();
        $news = $newsManager->find();
        
        $movie = $this->getMovie();
        $view = $this->getView();
        $view->setLayout($this->getLayoutViewPath('TwoColumnsBigLeft', LunomeModule::getModuleName()));
        $particleView = $view->getParticleViewManager()->load('NEWS_INDEX', $this->getParticleViewPath('News/Index'));
        $particleView->getDataManager()
            ->set('news', $news)
            ->set('movie', $movie)
            ->set('movieAccount', $this->getCurrentMovieAccount());
        
        $view->title = $movie->get('name').'的相关新闻';
    }
}