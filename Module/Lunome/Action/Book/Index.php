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
        $marks[BookService::MARK_UNMARKED]      = array('name'=>'所有',   'count'=>$this->getBookService()->countUnmarked());
        $marks[BookService::MARK_INTERESTED]    = array('name'=>'想读',   'count'=>$this->getBookService()->countMarked(BookService::MARK_INTERESTED));
        $marks[BookService::MARK_READING]       = array('name'=>'在读',   'count'=>$this->getBookService()->countMarked(BookService::MARK_READING));
        $marks[BookService::MARK_READ]          = array('name'=>'读过了',  'count'=>$this->getBookService()->countMarked(BookService::MARK_READ));
        $marks[BookService::MARK_IGNORED]       = array('name'=>'不喜欢',  'count'=>$this->getBookService()->countMarked(BookService::MARK_IGNORED));
        return $marks;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Index::getMarkActions()
    */
    protected function getMarkActions() {
        $actions = array();
        switch ( $this->currentMark ) {
        case BookService::MARK_UNMARKED :
            $actions[BookService::MARK_INTERESTED] = array( 'name'=>'想读', 'style'=>'success');
            $actions[BookService::MARK_READING]    = array( 'name'=>'在读', 'style'=>'primary');
            $actions[BookService::MARK_READ]       = array( 'name'=>'已读',  'style'=>'info');
            $actions[BookService::MARK_IGNORED]    = array( 'name'=>'不喜欢', 'style'=>'default');
            break;
        case BookService::MARK_INTERESTED:
            $actions[BookService::MARK_READING]    = array( 'name'=>'在读', 'style'=>'primary');
            $actions[BookService::MARK_READ]       = array( 'name'=>'已读',  'style'=>'info');
            $actions[BookService::MARK_IGNORED]    = array( 'name'=>'不喜欢', 'style'=>'default');
            break;
        case BookService::MARK_READING:
            $actions[BookService::MARK_READ]       = array( 'name'=>'读完了', 'style'=>'success');
            $actions[BookService::MARK_IGNORED]    = array( 'name'=>'不喜欢', 'style'=>'default');
            break;
        case BookService::MARK_READ:
            $actions[BookService::MARK_INTERESTED] = array( 'name'=>'还想读', 'style'=>'success');
            $actions[BookService::MARK_IGNORED]    = array( 'name'=>'不喜欢', 'style'=>'default');
            break;
        case BookService::MARK_IGNORED:
            $actions[BookService::MARK_INTERESTED] = array( 'name'=>'想读', 'style'=>'success');
            $actions[BookService::MARK_READING]    = array( 'name'=>'在读', 'style'=>'primary');
            $actions[BookService::MARK_READ]       = array( 'name'=>'已读',  'style'=>'info');
            break;
        default: break;
        }
        return $actions;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Index::getMediaTypeName()
     */
    protected function getMediaTypeName() {
        return '图书';
    }
}