<?php
/**
 * The action file for tv/index action.
 */
namespace X\Module\Lunome\Action\Book;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Service\Book\Service as BookService;
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
        $marks[BookService::MARK_UNMARKED]      = $this->getBookService()->countUnmarked();
        $marks[BookService::MARK_INTERESTED]    = $this->getBookService()->countMarked(BookService::MARK_INTERESTED);
        $marks[BookService::MARK_READING]       = $this->getBookService()->countMarked(BookService::MARK_READING);
        $marks[BookService::MARK_READ]          = $this->getBookService()->countMarked(BookService::MARK_READ);
        $marks[BookService::MARK_IGNORED]       = $this->getBookService()->countMarked(BookService::MARK_IGNORED);
        return $marks;
    }

    /**
     *  (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMainMediaList::getMediaIndexView()
     */
    protected function getMediaIndexView() {
        return $this->getParticleViewPath('Book/Index');
    }

    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMainMediaList::getActiveMenuItem()
     */
    protected function getActiveMenuItem() {
        return self::MENU_ITEM_BOOK;
    }
    
    /**
     * 
     * @param unknown $mark
     * @param number $page
     */
    public function runAction( $mark=BookService::MARK_UNMARKED, $page=1 ) {
        $this->getView()->title = "图书 | Lunome";
        
        $this->currentMark = intval($mark);
        $this->currentPage = $page;
    }
}