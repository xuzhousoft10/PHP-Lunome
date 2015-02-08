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
class Operate extends Visual {
    /**
     * 
     */
    public function runAction( $id ) {
        $view = $this->getView();
        $movieService = $this->getMovieService();
        $category = $movieService->getCategoryById($id);
        if ( null === $category ) {
            $this->throw404();
        }
        
        $viewName = 'BACKEND_MOVIE_CATEGORY_OPERATE_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/CategoryOperate');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'category', $category);
        $view->setDataToParticle($viewName, 'allCategories', $movieService->getCategories());
        
        $this->setPageTitle('电影类型编辑');
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}