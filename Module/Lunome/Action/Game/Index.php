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
     * @see \X\Module\Lunome\Util\Action\Media\Index::getMarkActions()
     */
    protected function getMarkActions() {
        $actions = array();
        switch ( $this->currentMark ) {
        case GameService::MARK_UNMARKED :
            $actions[GameService::MARK_INTERESTED]    = array('style'=>'success');
            $actions[GameService::MARK_PLAYING]       = array('style'=>'primary');
            $actions[GameService::MARK_PLAYED]        = array('style'=>'info');
            $actions[GameService::MARK_IGNORED]       = array('style'=>'default');
            break;
        case GameService::MARK_INTERESTED :
            $actions[GameService::MARK_UNMARKED]      = array('style'=>'warning');
            $actions[GameService::MARK_PLAYING]       = array('style'=>'primary');
            $actions[GameService::MARK_PLAYED]        = array('style'=>'info');
            $actions[GameService::MARK_IGNORED]       = array('style'=>'default');
            break;
        case GameService::MARK_PLAYING :
            $actions[GameService::MARK_PLAYED]        = array('style'=>'info');
            $actions[GameService::MARK_IGNORED]       = array('style'=>'default');
            break;
        case GameService::MARK_PLAYED :
            $actions[GameService::MARK_INTERESTED]    = array('style'=>'success');
            $actions[GameService::MARK_IGNORED]       = array('style'=>'default');
            break;
        case GameService::MARK_IGNORED :
            $actions[GameService::MARK_INTERESTED]    = array('style'=>'success');
            $actions[GameService::MARK_PLAYING]       = array('style'=>'primary');
            $actions[GameService::MARK_PLAYED]        = array('style'=>'info');
            break;
        default: break;
        }
        return $actions;
    }
}