<?php
/**
 * The action file for movie/index action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Service\Movie\Service as MovieService;
use X\Module\Lunome\Util\Action\Media\Index as MediaIndex;

/**
 * The action class for movie/index action.
 * @author Unknown
 */
class Index extends MediaIndex { 
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMainMediaList::getMarkInformation()
     */
    protected function getMarkInformation() {
        $markInfo = array();
        $markInfo[MovieService::MARK_UNMARKED]      = $this->getMovieService()->countUnmarked();
        $markInfo[MovieService::MARK_INTERESTED]    = $this->getMovieService()->countMarked(MovieService::MARK_INTERESTED);
        $markInfo[MovieService::MARK_WATCHED]       = $this->getMovieService()->countMarked(MovieService::MARK_WATCHED);
        $markInfo[MovieService::MARK_IGNORED]       = $this->getMovieService()->countMarked(MovieService::MARK_IGNORED);
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
    public function runAction($mark=MovieService::MARK_UNMARKED, $page=1) {
        $this->getView()->title = "电影 | Lunome";
        
        $this->currentMark = $mark;
        $this->currentPage = $page;
    }
}