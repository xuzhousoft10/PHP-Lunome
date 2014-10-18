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
        $marks[GameService::MARK_UNMARKED]      = array('name'=>'所有', 'count'=>$this->getGameService()->countUnmarked());
        $marks[GameService::MARK_INTERESTED]    = array('name'=>'想玩', 'count'=>$this->getGameService()->countMarked(GameService::MARK_INTERESTED));
        $marks[GameService::MARK_PLAYING]       = array('name'=>'在玩', 'count'=>$this->getGameService()->countMarked(GameService::MARK_PLAYING));
        $marks[GameService::MARK_PLAYED]        = array('name'=>'已玩', 'count'=>$this->getGameService()->countMarked(GameService::MARK_PLAYED));
        $marks[GameService::MARK_IGNORED]       = array('name'=>'不喜欢', 'count'=>$this->getGameService()->countMarked(GameService::MARK_IGNORED));
        return $marks;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Index::getMarkActions()
     */
    protected function getMarkActions() {
        $actions = array();
        switch ( $this->currentMark ) {
        case GameService::MARK_UNMARKED :
            $actions[GameService::MARK_INTERESTED]    = array('name'=>'想玩',    'style'=>'success');
            $actions[GameService::MARK_PLAYING]       = array('name'=>'在玩',    'style'=>'primary');
            $actions[GameService::MARK_PLAYED]        = array('name'=>'已玩',    'style'=>'info');
            $actions[GameService::MARK_IGNORED]       = array('name'=>'不喜欢',  'style'=>'default');
            break;
        case GameService::MARK_INTERESTED :
            $actions[GameService::MARK_UNMARKED]      = array('name'=>'不想玩了', 'style'=>'warning');
            $actions[GameService::MARK_PLAYING]       = array('name'=>'在玩',    'style'=>'primary');
            $actions[GameService::MARK_PLAYED]        = array('name'=>'已玩',    'style'=>'info');
            $actions[GameService::MARK_IGNORED]       = array('name'=>'不喜欢',  'style'=>'default');
            break;
        case GameService::MARK_PLAYING :
            $actions[GameService::MARK_PLAYED]        = array('name'=>'已玩',    'style'=>'info');
            $actions[GameService::MARK_IGNORED]       = array('name'=>'不喜欢',  'style'=>'default');
            break;
        case GameService::MARK_PLAYED :
            $actions[GameService::MARK_INTERESTED]    = array('name'=>'还想玩',    'style'=>'success');
            $actions[GameService::MARK_IGNORED]       = array('name'=>'不喜欢',  'style'=>'default');
            break;
        case GameService::MARK_IGNORED :
            $actions[GameService::MARK_INTERESTED]    = array('name'=>'想玩',    'style'=>'success');
            $actions[GameService::MARK_PLAYING]       = array('name'=>'在玩',    'style'=>'primary');
            $actions[GameService::MARK_PLAYED]        = array('name'=>'已玩',    'style'=>'info');
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
        return '游戏';
    }
}