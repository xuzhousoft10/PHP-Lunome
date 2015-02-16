<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie;

/**
 * 
 */
use X\Core\X;
use X\Module\Backend\Util\Action\Visual;

/**
 * 
 */
class Edit extends Visual {
    /**
     * 
     */
    public function runAction($id=null, $movie=null) {
        $view = $this->getView();
        $movieService = $this->getMovieService();
        $originalMovie = array(
            'id'=>null,'name'=>null, 'length'=>null,'date'=>null, 
            'region_id'=>null,'language_id'=>null,'introduction'=>null,'has_cover'=>0
        );
        if ( !empty($id) ) {
            if ( !$movieService->has($id) ) {
                $this->throw404();
            }
            $originalMovie = $movieService->get($id);
        }
        
        if ( !empty($movie) && is_array($movie) ) {
            $originalMovie = $movieService->update($movie, $id)->toArray();
            if ( isset($_FILES['cover']) && 0 === (int)$_FILES['cover']['error'] ) {
                if ( 1 === (int)$originalMovie['has_cover'] ) {
                    $movieService->deleteCover($originalMovie['id']);
                }
                
                $tmpCover = tempnam(sys_get_temp_dir(), 'BKEDMC');
                move_uploaded_file($_FILES['cover']['tmp_name'], $tmpCover);
                $originalMovie = $movieService->addCover($originalMovie['id'], $tmpCover)->toArray();
                unlink($tmpCover);
            }
            $this->gotoURL('/?module=backend&action=movie/detail', array('id'=>$originalMovie['id']));
        }
        
        if ( 0 === (int)$originalMovie['has_cover'] ) {
            $originalMovie['cover'] = $movieService->getMediaDefaultCoverURL();
        } else {
            $originalMovie['cover'] = $movieService->getCoverURL($originalMovie['id'], true);
        }
        
        $viewName = 'BACKEND_MOVIE_EDIT';
        $viewPath = $this->getParticleViewPath('Movie/Edit');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'movie', $originalMovie);
        $view->setDataToParticle($viewName, 'regions', $movieService->getRegions());
        $view->setDataToParticle($viewName, 'languages', $movieService->getLanguages());
        
        $this->setPageTitle(empty($id) ? '新建电影信息' : '编辑电影《'.$movie['name'].'》');
        $this->setMenuItemActived(self::MENU_ITEM_MOVIE);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Backend\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        parent::beforeDisplay();
        $assetsURL = $this->getAssetsURL();
        $view = $this->getView();
        
        $view->addCssLink('bootstrap-datapicker', $assetsURL.'/library/bootstrap/plugin/bootstrap-datepicker/css/datepicker3.css');
        $view->addScriptFile('bootstrap-datapicker', $assetsURL.'/library/bootstrap/plugin/bootstrap-datepicker/js/bootstrap-datepicker.js');
        $view->addScriptFile('bootstrap-datapicker-local', $assetsURL.'/library/bootstrap/plugin/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');
    }
}