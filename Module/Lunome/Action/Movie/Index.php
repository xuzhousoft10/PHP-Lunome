<?php
/**
 * The action file for movie/index action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\VisualMainMediaList;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/index action.
 * @author Unknown
 */
class Index extends VisualMainMediaList { 
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMainMediaList::getMediaService()
     */
    protected function getMediaService() {
        return $this->getMovieService();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMainMediaList::getMarkInformation()
     */
    protected function getMarkInformation() {
        $markInfo = array();
        $markInfo['unmarked']   = $this->getMovieService()->countUnmarked();
        $markInfo['interested'] = $this->getMovieService()->countMarked(MovieService::MARK_INTERESTED);
        $markInfo['watched']    = $this->getMovieService()->countMarked(MovieService::MARK_WATCHED);
        $markInfo['ignored']    = $this->getMovieService()->countMarked(MovieService::MARK_IGNORED);
        return $markInfo;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMainMediaList::getMediaIndexView()
     */
    protected function getMediaIndexView() {
        return $this->getParticleViewPath('Movie/Index');
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMainMediaList::getActiveMenuItem()
     */
    protected function getActiveMenuItem() {
        return self::MENU_ITEM_MOVIE;
    }
    
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction($mark=MovieService::MARK_NAME_UNMARKED, $page=1) {
        $this->currentMark = ( MovieService::MARK_NAME_UNMARKED == $mark ) ? null : $mark;
    }
}