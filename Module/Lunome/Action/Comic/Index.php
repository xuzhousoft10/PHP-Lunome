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
     * @see \X\Module\Lunome\Util\Action\Media\Index::getMarkActions()
     */
    protected function getMarkActions() {
        $actions = array();
        switch ( $this->currentMark ) {
        case ComicService::MARK_UNMARKED:
            $actions[ComicService::MARK_INTERESTED] = array('style'=>'success');
            $actions[ComicService::MARK_WATCHING]   = array('style'=>'primary');
            $actions[ComicService::MARK_WATCHED]    = array('style'=>'info');
            $actions[ComicService::MARK_IGNORED]    = array('style'=>'default');
            break;
        case ComicService::MARK_INTERESTED:
            $actions[ComicService::MARK_UNMARKED]   = array('style'=>'success');
            $actions[ComicService::MARK_WATCHING]   = array('style'=>'primary');
            $actions[ComicService::MARK_WATCHED]    = array('style'=>'info');
            break;
        case ComicService::MARK_WATCHING:
            $actions[ComicService::MARK_WATCHED]    = array('style'=>'info');
            $actions[ComicService::MARK_IGNORED]    = array('style'=>'default');
            break;
        case ComicService::MARK_WATCHED:
            $actions[ComicService::MARK_INTERESTED] = array('style'=>'success');
            $actions[ComicService::MARK_IGNORED]    = array('style'=>'default');
            break;
        case ComicService::MARK_IGNORED:
            $actions[ComicService::MARK_INTERESTED] = array('style'=>'success');
            $actions[ComicService::MARK_WATCHING]   = array('style'=>'primary');
            $actions[ComicService::MARK_WATCHED]    = array('style'=>'info');
            break;
        default:break;
        }
        return $actions;
    }
}