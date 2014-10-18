<?php
/**
 * The action file for tv/index action.
 */
namespace X\Module\Lunome\Action\Tv;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Service\Tv\Service as TvService;
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
        $marks[TvService::MARK_UNMARKED]     = $this->getTvService()->countUnmarked();
        $marks[TvService::MARK_INTERESTED]   = $this->getTvService()->countMarked(TvService::MARK_INTERESTED);
        $marks[TvService::MARK_WATCHING]     = $this->getTvService()->countMarked(TvService::MARK_WATCHING);
        $marks[TvService::MARK_WATCHED]      = $this->getTvService()->countMarked(TvService::MARK_WATCHED);
        $marks[TvService::MARK_IGNORED]      = $this->getTvService()->countMarked(TvService::MARK_IGNORED);
        return $marks;
    }

    /**
     *  (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMainMediaList::getMediaIndexView()
     */
    protected function getMediaIndexView() {
        return $this->getParticleViewPath('Tv/Index');
    }

    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMainMediaList::getActiveMenuItem()
     */
    protected function getActiveMenuItem() {
        return self::MENU_ITEM_TV;
    }
    
    /**
     * 
     * @param unknown $mark
     * @param number $page
     */
    public function runAction( $mark=TvService::MARK_UNMARKED, $page=1 ) {
        $this->getView()->title = "电视 | Lunome";
        
        $this->currentMark = intval($mark);
        $this->currentPage = $page;
    }
}