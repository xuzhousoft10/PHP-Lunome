<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Language;

/**
 * 
 */
use X\Module\Backend\Util\Action\Visual;

/**
 * 
 */
class Operate extends Visual {
    /**
     * 
     */
    public function runAction( $id ) {
        $view = $this->getView();
        $movieService = $this->getMovieService();
        $language = $movieService->getLanguageById($id);
        if ( null === $language ) {
            $this->throw404();
        }
        
        $viewName = 'BACKEND_MOVIE_LANGUAGE_OPERATE_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/LanguageOperate');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'language', $language);
        $view->setDataToParticle($viewName, 'allLangeuages', $movieService->getLanguages());
        
        $this->setPageTitle('电影语言操作');
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}