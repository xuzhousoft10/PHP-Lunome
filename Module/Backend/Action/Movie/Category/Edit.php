<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Category;

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
    public function runAction( $id=null, $category=null) {
        $view = $this->getView();
        $movieService = $this->getMovieService();
        
        if ( !empty($category) ) {
            if ( !empty($id) ) {
                $category = $movieService->updateCategory($id, $category)->toArray();
            } else {
                $category = $movieService->addCategory($category)->toArray();
            }
            $this->gotoURL('/?module=backend&action=movie/category/index');
        } else {
            $category = array('id'=>null, 'name'=>'', 'beg_message'=>'','recommend_message'=>'', 'share_message'=>'');
            if ( !empty($id) ) {
                $category = $movieService->getCategoryById($id)->toArray();
            }
        }
        
        $viewName = 'BACKEND_MOVIE_CATEGORY_EDIT_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/CategoryEdit');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'category', $category);
        
        $this->setPageTitle('电影类型编辑');
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}