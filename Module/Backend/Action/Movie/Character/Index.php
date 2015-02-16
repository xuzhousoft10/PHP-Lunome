<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Character;

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
        $pageSize = $moduleConfig->get('movie_character_index_page_size');
        
        if ( !$movieService->has($id) ) {
            $this->throw404();
        }
        
        $movie = $movieService->get($id);
        $characters = $movieService->getCharacters($id, ($page-1)*$pageSize, $pageSize);
        foreach ( $characters as $characterIndex => $character ) {
            $characters[$characterIndex] = $character->toArray();
            $characters[$characterIndex]['image'] = $movieService->getCharacterUrlById($character->id);
        }
        
        $viewName = 'BACKEND_MOVIE_CHARACTER_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/Characters');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'characters', $characters);
        $view->setDataToParticle($viewName, 'movie', $movie);
        
        $viewName = 'BACKEND_MOVIE_CHARACTER_INDEX_PAGER';
        $viewPath = $this->getParticleViewPath('Util/Pager');
        $view->loadParticle($viewName, $viewPath);
        $totalCount = $movieService->countCharacters($id);
        $view->setDataToParticle($viewName, 'totalCount', $totalCount);
        $view->setDataToParticle($viewName, 'pageSize', $pageSize);
        $view->setDataToParticle($viewName, 'currentPage', $page);
        $pagerParams = array_merge(array('module'=>'backend', 'action'=>'movie/character/index'), array('id'=>$id));
        $view->setDataToParticle($viewName, 'parameters', http_build_query($pagerParams));
        
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}