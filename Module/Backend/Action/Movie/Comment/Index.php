<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Comment;

/**
 * 
 */
use X\Module\Backend\Util\Action\Visual;

/**
 * 
 */
class Index extends Visual {
    /**
     * 
     */
    public function runAction($id, $page=1) {
        $moduleConfig = $this->getModule()->getConfiguration();
        $movieService = $this->getMovieService();
        $accountManager = $this->getUserService()->getAccount();
        $view = $this->getView();
        $page = (int)$page;
        $pageSize = $moduleConfig->get('movie_comment_index_page_size');
        
        if ( !$movieService->has($id) ) {
            $this->throw404();
        }
        
        $movie = $movieService->get($id);
        $comments = $movieService->getShortComments($id, null, ($page-1)*$pageSize, $pageSize);
        foreach ( $comments as $index => $comment ) {
            $comments[$index] = $comment->toArray();
            $comments[$index]['commented_by'] = $accountManager->getInformation($comment->commented_by)->nickname;
        }
        
        $viewName = 'BACKEND_MOVIE_COMMENT_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/Comments');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'comments', $comments);
        $view->setDataToParticle($viewName, 'movie', $movie);
        
        $viewName = 'BACKEND_MOVIE_COMMENT_INDEX_PAGER';
        $viewPath = $this->getParticleViewPath('Util/Pager');
        $view->loadParticle($viewName, $viewPath);
        $totalCount = $movieService->countShortComments($id);
        $view->setDataToParticle($viewName, 'totalCount', $totalCount);
        $view->setDataToParticle($viewName, 'pageSize', $pageSize);
        $view->setDataToParticle($viewName, 'currentPage', $page);
        $pagerParams = array_merge(array('module'=>'backend', 'action'=>'movie/comment/index'), array('id'=>$id));
        $view->setDataToParticle($viewName, 'parameters', http_build_query($pagerParams));
        
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}