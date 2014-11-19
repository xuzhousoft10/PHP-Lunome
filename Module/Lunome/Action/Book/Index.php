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
     * @see \X\Module\Lunome\Util\Action\Media\Index::getMarkActions()
    */
    protected function getMarkActions() {
        $actions = array();
        switch ( $this->currentMark ) {
        case BookService::MARK_UNMARKED :
            $actions[BookService::MARK_INTERESTED] = array('style'=>'success');
            $actions[BookService::MARK_READING]    = array('style'=>'primary');
            $actions[BookService::MARK_READ]       = array('style'=>'info');
            $actions[BookService::MARK_IGNORED]    = array('style'=>'default');
            break;
        case BookService::MARK_INTERESTED:
            $actions[BookService::MARK_READING]    = array('style'=>'primary');
            $actions[BookService::MARK_READ]       = array('style'=>'info');
            $actions[BookService::MARK_IGNORED]    = array('style'=>'default');
            break;
        case BookService::MARK_READING:
            $actions[BookService::MARK_READ]       = array('style'=>'success');
            $actions[BookService::MARK_IGNORED]    = array('style'=>'default');
            break;
        case BookService::MARK_READ:
            $actions[BookService::MARK_INTERESTED] = array('style'=>'success');
            $actions[BookService::MARK_IGNORED]    = array('style'=>'default');
            break;
        case BookService::MARK_IGNORED:
            $actions[BookService::MARK_INTERESTED] = array('style'=>'success');
            $actions[BookService::MARK_READING]    = array('style'=>'primary');
            $actions[BookService::MARK_READ]       = array('style'=>'info');
            break;
        default: break;
        }
        return $actions;
    }
}