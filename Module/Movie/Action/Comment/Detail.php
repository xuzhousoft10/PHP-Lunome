<?php
namespace X\Module\Movie\Action\Comment;
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
    public function runAction( $movie, $comment ) {
        $movie = $this->getMovie();
        $manager = $movie->getShortCommentManager();
        
        $comment = $manager->get($comment);
        if ( null === $comment ) {
            $this->throw404();
        }
        
        $view = $this->getView();
        $view->setLayout($this->getLayoutViewPath('TwoColumnsBigLeft', LunomeModule::getModuleName()));
        $particleView = $view->getParticleViewManager()->load('SHORT_COMMENT_DETAIL', $this->getParticleViewPath('Comment/Detail'));
        $particleView->getDataManager()
            ->set('comment', $comment)
            ->set('movie', $movie)
            ->set('movieAccount', $this->getCurrentMovieAccount())
            ->set('currentAccount', $this->getCurrentAccount());
        
        $view->title = $movie->get('name').'的一句话点评 -- '.mb_substr($comment->get('content'), 0, 10, 'UTF-8').'...';
    }
}