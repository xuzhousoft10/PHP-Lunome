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
class Edit extends Visual {
    /**
     * 
     */
    public function runAction( $id=null, $language=null) {
        $view = $this->getView();
        $movieService = $this->getMovieService();
        
        if ( !empty($language) ) {
            if ( !empty($id) ) {
                $language = $movieService->updateLanguage($id, $language)->toArray();
            } else {
                $language = $movieService->addLanguage($language)->toArray();
            }
            $this->gotoURL('/?module=backend&action=movie/language/index');
        } else {
            $language = array('id'=>null, 'name'=>'');
            if ( !empty($id) ) {
                $language = $movieService->getLanguageById($id)->toArray();
            }
        }
        
        $viewName = 'BACKEND_MOVIE_LANGUAGE_EDIT_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/LanguageEdit');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'language', $language);
        
        $this->setPageTitle('电影语言编辑');
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}