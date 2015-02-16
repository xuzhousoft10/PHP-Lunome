<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Dialogue;

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
        $view = $this->getView();
        $page = (int)$page;
        $pageSize = $moduleConfig->get('movie_dialogue_index_page_size');
        
        if ( !$movieService->has($id) ) {
            $this->throw404();
        }
        
        $movie = $movieService->get($id);
        $dialogues = $movieService->getClassicDialogues($id, ($page-1)*$pageSize, $pageSize);
        foreach ( $dialogues as $index => $dialogue ) {
            $dialogues[$index] = $dialogue->toArray();
        }
        
        $viewName = 'BACKEND_MOVIE_DIALOGUE_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/Dialogues');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'dialogues', $dialogues);
        $view->setDataToParticle($viewName, 'movie', $movie);
        
        $viewName = 'BACKEND_MOVIE_DIALOGUE_INDEX_PAGER';
        $viewPath = $this->getParticleViewPath('Util/Pager');
        $view->loadParticle($viewName, $viewPath);
        $totalCount = $movieService->countClasicDialogues($id);
        $view->setDataToParticle($viewName, 'totalCount', $totalCount);
        $view->setDataToParticle($viewName, 'pageSize', $pageSize);
        $view->setDataToParticle($viewName, 'currentPage', $page);
        $pagerParams = array_merge(array('module'=>'backend', 'action'=>'movie/dialogue/index'), array('id'=>$id));
        $view->setDataToParticle($viewName, 'parameters', http_build_query($pagerParams));
        
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}