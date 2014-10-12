<?php
/**
 * The action file for tv/index action.
 */
namespace X\Module\Lunome\Action\Game;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Service\Game\Service as GameService;
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
        $marks[GameService::MARK_UNMARKED]      = $this->getGameService()->countUnmarked();
        $marks[GameService::MARK_INTERESTED]    = $this->getGameService()->countMarked(GameService::MARK_INTERESTED);
        $marks[GameService::MARK_PLAYING]       = $this->getGameService()->countMarked(GameService::MARK_PLAYING);
        $marks[GameService::MARK_PLAYED]        = $this->getGameService()->countMarked(GameService::MARK_PLAYED);
        $marks[GameService::MARK_IGNORED]       = $this->getGameService()->countMarked(GameService::MARK_IGNORED);
        return $marks;
    }

    /**
     *  (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMainMediaList::getMediaIndexView()
     */
    protected function getMediaIndexView() {
        return $this->getParticleViewPath('Game/Index');
    }

    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMainMediaList::getActiveMenuItem()
     */
    protected function getActiveMenuItem() {
        return self::MENU_ITEM_GAME;
    }
    
    /**
     * 
     * @param unknown $mark
     * @param number $page
     */
    public function runAction( $mark=GameService::MARK_UNMARKED, $page=1 ) {
        $this->currentMark = intval($mark);
        $this->currentPage = $page;
    }
}