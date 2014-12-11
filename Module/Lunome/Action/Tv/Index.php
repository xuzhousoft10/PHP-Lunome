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
     * @see \X\Module\Lunome\Util\Action\Media\Index::getMarkActions()
     */
    protected function getMarkActions() {
        $actions = array();
        switch ($this->currentMark) {
        case TvService::MARK_UNMARKED:
            $actions[TvService::MARK_INTERESTED]   = array('style'=>'success');
            $actions[TvService::MARK_WATCHING]     = array('style'=>'primary');
            $actions[TvService::MARK_WATCHED]      = array('style'=>'info');
            $actions[TvService::MARK_IGNORED]      = array('style'=>'default');
            break;
        case TvService::MARK_INTERESTED:
            $actions[TvService::MARK_UNMARKED]     = array('style'=>'warning');
            $actions[TvService::MARK_WATCHING]     = array('style'=>'primary');
            $actions[TvService::MARK_WATCHED]      = array('style'=>'info');
            $actions[TvService::MARK_IGNORED]      = array('style'=>'default');
            break;
        case TvService::MARK_WATCHING:
            $actions[TvService::MARK_WATCHED]      = array('style'=>'info');
            $actions[TvService::MARK_IGNORED]      = array('style'=>'default');
            break;
        case TvService::MARK_WATCHED:
            $actions[TvService::MARK_INTERESTED]   = array('style'=>'success');
            $actions[TvService::MARK_IGNORED]      = array('style'=>'default');
            break;
        case TvService::MARK_IGNORED:
            $actions[TvService::MARK_INTERESTED]   = array('style'=>'success');
            $actions[TvService::MARK_WATCHING]     = array('style'=>'primary');
            $actions[TvService::MARK_WATCHED]      = array('style'=>'info');
            break;
        default: break;
        }
        return $actions;
    }
}