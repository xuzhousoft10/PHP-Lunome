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
        $marks[ComicService::MARK_UNMARKED]     = array('name'=>'所有', 'count'=>$this->getComicService()->countUnmarked());
        $marks[ComicService::MARK_INTERESTED]   = array('name'=>'想看', 'count'=>$this->getComicService()->countMarked(ComicService::MARK_INTERESTED));
        $marks[ComicService::MARK_WATCHING]     = array('name'=>'在看', 'count'=>$this->getComicService()->countMarked(ComicService::MARK_WATCHING));
        $marks[ComicService::MARK_WATCHED]      = array('name'=>'已看', 'count'=>$this->getComicService()->countMarked(ComicService::MARK_WATCHED));
        $marks[ComicService::MARK_IGNORED]      = array('name'=>'不喜欢', 'count'=>$this->getComicService()->countMarked(ComicService::MARK_IGNORED));
        return $marks;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Index::getMarkActions()
     */
    protected function getMarkActions() {
        $actions = array();
        switch ( $this->currentMark ) {
        case ComicService::MARK_UNMARKED:
            $actions[ComicService::MARK_INTERESTED] = array( 'name'=>'想看',    'style'=>'success');
            $actions[ComicService::MARK_WATCHING]   = array( 'name'=>'在看',    'style'=>'primary');
            $actions[ComicService::MARK_WATCHED]    = array( 'name'=>'看过了',   'style'=>'info');
            $actions[ComicService::MARK_IGNORED]    = array( 'name'=>'不喜欢',   'style'=>'default');
            break;
        case ComicService::MARK_INTERESTED:
            $actions[ComicService::MARK_UNMARKED]   = array( 'name'=>'不想看了', 'style'=>'success');
            $actions[ComicService::MARK_WATCHING]   = array( 'name'=>'在看',    'style'=>'primary');
            $actions[ComicService::MARK_WATCHED]    = array( 'name'=>'看过了',   'style'=>'info');
            break;
        case ComicService::MARK_WATCHING:
            $actions[ComicService::MARK_WATCHED]    = array( 'name'=>'看完了',   'style'=>'info');
            $actions[ComicService::MARK_IGNORED]    = array( 'name'=>'不喜欢',   'style'=>'default');
            break;
        case ComicService::MARK_WATCHED:
            $actions[ComicService::MARK_INTERESTED] = array( 'name'=>'还想看',    'style'=>'success');
            $actions[ComicService::MARK_IGNORED]    = array( 'name'=>'不喜欢',   'style'=>'default');
            break;
        case ComicService::MARK_IGNORED:
            $actions[ComicService::MARK_INTERESTED] = array( 'name'=>'想看了',    'style'=>'success');
            $actions[ComicService::MARK_WATCHING]   = array( 'name'=>'在看',    'style'=>'primary');
            $actions[ComicService::MARK_WATCHED]    = array( 'name'=>'看过了',   'style'=>'info');
            break;
        default:break;
        }
        return $actions;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Index::getMediaTypeName()
     */
    protected function getMediaTypeName() {
        return '动漫';
    }
}