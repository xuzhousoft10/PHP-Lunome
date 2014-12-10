<?php
/**
 * The action file for movie/index action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\VisualMain;

/**
 * The action class for movie/index action.
 * @author Unknown
 */
class Edit extends VisualMain { 
    /**
     * 
     */
    protected function runAction( $id=null, $movie=null, $categories=null ) {
        $this->activeMenuItem(self::MENU_ITEM_MOVIE);
        $movieService = $this->getMovieService();
        
        if ( !empty($id) ) {
            $movieData = $movieService->get($id);
        } else {
            $movieData = array();
        }
        
        if( !empty($movie) ) {
            $movieData = $this->saveMovie($movie, $id, $categories);
        }
        
        $regions = $movieService->getRegions();
        $languages = $movieService->getLanguages();
        $categories = $movieService->getCategories();
        $movieCategories = empty($id) ? array() : $movieService->getCategoriesByMovieId($movieData['id']);
        foreach ( $movieCategories as $index => $movieCategory ) {
            $movieCategories[$index] = $movieCategory->id;
        }
        
        if ( isset($movieData['has_cover']) && 1 === $movieData['has_cover']*1 ) {
            $coverURL = $movieService->getMediaCoverURL($id);
        } else {
            $coverURL = $movieService->getMediaDefaultCoverURL();
        }
        $name   = 'MOVIE_EDIT';
        $path   = $this->getParticleViewPath('Movie/Edit');
        $option = array();
        $data   = array(
            'movie'             => $movieData,
            'regions'           => $regions, 
            'languages'         => $languages, 
            'coverURL'          => $coverURL,
            'categories'        => $categories,
            'movieCategories'   => $movieCategories,
        );
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        $title = empty($id) ? '添加新电影' : "{$movieData['name']} -- 编辑";
        $this->getView()->title = $title;
    }
    
    /**
     * @param unknown $movie
     * @param unknown $id
     * @return Ambigous <\X\Module\Lunome\Util\Service\Ambigous, \X\Util\Model\Basic>
     */
    private function saveMovie($movie, $id, $categories) {
        $movieService = $this->getMovieService();
        $movieData = $movieService->update($movie, $id);
        if ( $movieData->hasError() ) {
            return $movieData;
        }
        
        $movieService->setCategories($movieData->id, $categories);
        if ( isset($_FILES['movie']) && 0 === $_FILES['movie']['error']['cover'] ) {
            $tempPath = tempnam(sys_get_temp_dir(), 'UPCV');
            move_uploaded_file($_FILES['movie']['tmp_name']['cover'], $tempPath);
            if ( 1 === $movieData->has_cover*1 ) {
                $movieService->deleteCover($movieData->id);
            }
            $movieService->addCover($movieData->id, $tempPath);
            unlink($tempPath);
        }
        
        $this->gotoURL('/?module=lunome&action=movie/detail', array('id'=>$movieData->id));
        return $movieData;
    }
}