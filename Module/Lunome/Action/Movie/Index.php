<?php
/**
 * The action file for movie/index action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Service\Movie\Service as MovieService;
use X\Module\Lunome\Util\Action\Media\Index as MediaIndex;

/**
 * The action class for movie/index action.
 * @author Unknown
 */
class Index extends MediaIndex { 
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMainMediaList::getMarkInformation()
     */
    protected function getMarkInformation() {
        $markInfo = array();
        $markInfo[MovieService::MARK_UNMARKED]      = array('name'=>'所有', 'count'=>$this->getMovieService()->countUnmarked());
        $markInfo[MovieService::MARK_INTERESTED]    = array('name'=>'想看', 'count'=>$this->getMovieService()->countMarked(MovieService::MARK_INTERESTED));
        $markInfo[MovieService::MARK_WATCHED]       = array('name'=>'已看', 'count'=>$this->getMovieService()->countMarked(MovieService::MARK_WATCHED));
        $markInfo[MovieService::MARK_IGNORED]       = array('name'=>'不喜欢', 'count'=>$this->getMovieService()->countMarked(MovieService::MARK_IGNORED));
        return $markInfo;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Index::getMarkActions()
     */
    protected function getMarkActions() {
        $actions = array();
        switch ( $this->currentMark ) {
        case MovieService::MARK_UNMARKED:
            $actions[MovieService::MARK_INTERESTED]    = array('name'=>'想看',     'style'=>'success');
            $actions[MovieService::MARK_WATCHED]       = array('name'=>'已看',     'style'=>'info');
            $actions[MovieService::MARK_IGNORED]       = array('name'=>'不喜欢',    'style'=>'default');
            break;
        case MovieService::MARK_INTERESTED:
            $actions[MovieService::MARK_UNMARKED]      = array('name'=>'不想看了',  'style'=>'warning');
            $actions[MovieService::MARK_WATCHED]       = array('name'=>'已看',     'style'=>'info');
            $actions[MovieService::MARK_IGNORED]       = array('name'=>'不喜欢',    'style'=>'default');
            break;
        case MovieService::MARK_WATCHED:
            $actions[MovieService::MARK_INTERESTED]    = array('name'=>'还想看',     'style'=>'success');
            $actions[MovieService::MARK_IGNORED]       = array('name'=>'不喜欢',    'style'=>'default');
            break;
        case MovieService::MARK_IGNORED:
            $actions[MovieService::MARK_INTERESTED]    = array('name'=>'想看',     'style'=>'success');
            $actions[MovieService::MARK_WATCHED]       = array('name'=>'已看',     'style'=>'info');
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
        return '电影';
    }
}