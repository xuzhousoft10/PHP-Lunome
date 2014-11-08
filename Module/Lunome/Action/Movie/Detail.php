<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Media\Detail as MediaDetail;
use X\Module\Lunome\Service\Movie\Service;

/**
 * The action class for movie/ignore action.
 * @method \X\Module\Lunome\Service\Movie\Service getMovieService()
 * @author Unknown
 */
class Detail extends MediaDetail {
    /**
     *  (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Detail::getMarkNames()
     */
    protected function getMarkNames() {
        return array(
            Service::MARK_UNMARKED      => '未标记',
            Service::MARK_INTERESTED    => '想看',
            Service::MARK_WATCHED       => '已看',
            Service::MARK_IGNORED       => '不喜欢'
        );
    }

    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Detail::getMarkStyles()
     */
    protected function getMarkStyles() {
        return array(
            Service::MARK_UNMARKED      => 'warning',
            Service::MARK_INTERESTED    => 'success',
            Service::MARK_WATCHED       => 'info',
            Service::MARK_IGNORED       => 'default'
        );
    }

    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Detail::getMediaName()
     */
    protected function getMediaName() {
        return '电影';
    }
}