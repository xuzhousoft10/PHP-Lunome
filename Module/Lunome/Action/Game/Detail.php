<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Game;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Media\Detail as MediaDetail;
use X\Module\Lunome\Service\Game\Service;

/**
 * The action class for movie/ignore action.
 * @method \X\Module\Lunome\Service\Movie\Service getMovieService()
 * @author Unknown
 */
class Detail extends MediaDetail {
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Detail::getMarkNames()
     */
    protected function getMarkNames() {
        $marks = array(
            Service::MARK_UNMARKED      => '未标记',
            Service::MARK_INTERESTED    => '想玩',
            Service::MARK_PLAYING       => '在玩',
            Service::MARK_PLAYED        => '已玩',
            Service::MARK_IGNORED       => '不喜欢',
        );
        return $marks;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Detail::getMarkStyles()
     */
    protected function getMarkStyles() {
        $marks = array(
            Service::MARK_UNMARKED      => 'warning',
            Service::MARK_INTERESTED    => 'success',
            Service::MARK_PLAYING       => 'primary',
            Service::MARK_PLAYED        => 'info',
            Service::MARK_IGNORED       => 'default',
        );
        return $marks;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Detail::getMediaName()
     */
    protected function getMediaName() {
        return '游戏';
    }
}