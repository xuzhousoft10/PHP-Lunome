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
        $marks[TvService::MARK_UNMARKED]     = array('name'=>'所有', 'count'=>$this->getTvService()->countUnmarked());
        $marks[TvService::MARK_INTERESTED]   = array('name'=>'想看', 'count'=>$this->getTvService()->countMarked(TvService::MARK_INTERESTED));
        $marks[TvService::MARK_WATCHING]     = array('name'=>'在看', 'count'=>$this->getTvService()->countMarked(TvService::MARK_WATCHING));
        $marks[TvService::MARK_WATCHED]      = array('name'=>'已看', 'count'=>$this->getTvService()->countMarked(TvService::MARK_WATCHED));
        $marks[TvService::MARK_IGNORED]      = array('name'=>'不喜欢', 'count'=>$this->getTvService()->countMarked(TvService::MARK_IGNORED));
        return $marks;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Index::getMarkActions()
     */
    protected function getMarkActions() {
        $actions = array();
        switch ($this->currentMark) {
        case TvService::MARK_UNMARKED:
            $actions[TvService::MARK_INTERESTED]   = array('name'=>'想看', 'style'=>'success');
            $actions[TvService::MARK_WATCHING]     = array('name'=>'在看', 'style'=>'primary');
            $actions[TvService::MARK_WATCHED]      = array('name'=>'已看', 'style'=>'info');
            $actions[TvService::MARK_IGNORED]      = array('name'=>'不喜欢', 'style'=>'default');
            break;
        case TvService::MARK_INTERESTED:
            $actions[TvService::MARK_UNMARKED]     = array('name'=>'不想看了', 'style'=>'warning');
            $actions[TvService::MARK_WATCHING]     = array('name'=>'在看', 'style'=>'primary');
            $actions[TvService::MARK_WATCHED]      = array('name'=>'已看', 'style'=>'info');
            $actions[TvService::MARK_IGNORED]      = array('name'=>'不喜欢', 'style'=>'default');
            break;
        case TvService::MARK_WATCHING:
            $actions[TvService::MARK_WATCHED]      = array('name'=>'已看', 'style'=>'info');
            $actions[TvService::MARK_IGNORED]      = array('name'=>'不喜欢', 'style'=>'default');
            break;
        case TvService::MARK_WATCHED:
            $actions[TvService::MARK_INTERESTED]   = array('name'=>'还想看', 'style'=>'success');
            $actions[TvService::MARK_IGNORED]      = array('name'=>'不喜欢', 'style'=>'default');
            break;
        case TvService::MARK_IGNORED:
            $actions[TvService::MARK_INTERESTED]   = array('name'=>'想看', 'style'=>'success');
            $actions[TvService::MARK_WATCHING]     = array('name'=>'在看', 'style'=>'primary');
            $actions[TvService::MARK_WATCHED]      = array('name'=>'已看', 'style'=>'info');
            break;
        default: break;
        }
        return $actions;
    }

    /**
     *  (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Index::getMediaTypeName()
     */
    protected function getMediaTypeName() {
        return '电视剧';
    }
}