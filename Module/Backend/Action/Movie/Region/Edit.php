<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Region;

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
    public function runAction( $id=null, $region=null) {
        $view = $this->getView();
        $movieService = $this->getMovieService();
        
        if ( !empty($region) ) {
            if ( !empty($id) ) {
                $region = $movieService->updateRegion($id, $region)->toArray();
            } else {
                $region = $movieService->addRegion($region)->toArray();
            }
            $this->gotoURL('/?module=backend&action=movie/region/index');
        } else {
            $language = array('id'=>null, 'name'=>'');
            if ( !empty($id) ) {
                $region = $movieService->getRegionById($id)->toArray();
            }
        }
        
        $viewName = 'BACKEND_MOVIE_REGION_EDIT_INDEX';
        $viewPath = $this->getParticleViewPath('Movie/RegionEdit');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'region', $region);
        
        $this->setPageTitle('电影区域编辑');
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
}