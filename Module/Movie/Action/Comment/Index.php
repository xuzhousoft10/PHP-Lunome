<?php
namespace X\Module\Movie\Action\Comment;
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
        $shortCommentManager = $this->getMovie()->getShortCommentManager();
        $comments = $shortCommentManager->find();
        
        $movie = $this->getMovie();
        $view = $this->getView();
        $view->setLayout($this->getLayoutViewPath('TwoColumnsBigLeft', LunomeModule::getModuleName()));
        $particleView = $view->getParticleViewManager()->load('SHORT_COMMENT_INDEX', $this->getParticleViewPath('Comment/Index'));
        $particleView->getDataManager()
            ->set('comments', $comments)
            ->set('movie', $movie)
            ->set('movieAccount', $this->getCurrentMovieAccount());
        
        $view->title = $movie->get('name').'的一句话点评';
    }
}