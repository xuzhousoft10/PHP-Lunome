<?php
/**
 * The action file for tv/index action.
 */
namespace X\Module\Lunome\Action\Comic;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Service\Comic\Service as ComicService;
use X\Module\Lunome\Util\Action\Media\Index as MediaIndex;

/**
 * The action class for tv/index action.
 * @author Unknown
 */
class Index extends MediaIndex {
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMainMediaList::getMarkInformation()
     */
    protected function getMarkInformation() {
        $marks = array();
        $marks[ComicService::MARK_UNMARKED]     = $this->getComicService()->countUnmarked();
        $marks[ComicService::MARK_INTERESTED]   = $this->getComicService()->countMarked(ComicService::MARK_INTERESTED);
        $marks[ComicService::MARK_WATCHING]     = $this->getComicService()->countMarked(ComicService::MARK_WATCHING);
        $marks[ComicService::MARK_WATCHED]      = $this->getComicService()->countMarked(ComicService::MARK_WATCHED);
        $marks[ComicService::MARK_IGNORED]      = $this->getComicService()->countMarked(ComicService::MARK_IGNORED);
        return $marks;
    }

    /**
     *  (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMainMediaList::getMediaIndexView()
     */
    protected function getMediaIndexView() {
        return $this->getParticleViewPath('Comic/Index');
    }

    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMainMediaList::getActiveMenuItem()
     */
    protected function getActiveMenuItem() {
        return self::MENU_ITEM_COMIC;
    }
    
    /**
     * 
     * @param unknown $mark
     * @param number $page
     */
    public function runAction( $mark=ComicService::MARK_UNMARKED, $page=1 ) {
        $this->getView()->title = "动漫 | Lunome";
        
        $this->currentMark = intval($mark);
        $this->currentPage = $page;
    }
}