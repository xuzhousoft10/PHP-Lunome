<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Tv;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Media\Detail as MediaDetail;
use X\Module\Lunome\Service\Tv\Service;

/**
 * The action class for movie/ignore action.
 * @method \X\Module\Lunome\Service\Tv\Service getTvService()
 * @author Unknown
 */
class Detail extends MediaDetail { 
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Detail::getMarkStyles()
     */
    protected function getMarkStyles() {
        $marks = array(
            Service::MARK_UNMARKED      => 'warning',
            Service::MARK_INTERESTED    => 'success',
            Service::MARK_WATCHING      => 'primary',
            Service::MARK_WATCHED       => 'info',
            Service::MARK_IGNORED       => 'default',
        );
        return $marks;
    }
}