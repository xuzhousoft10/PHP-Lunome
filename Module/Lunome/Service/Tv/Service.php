<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\Tv;

/**
 * 
 */
use X\Module\Lunome\Util\Service\Media;

/**
 * The service class
 */
class Service extends Media {
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Service\Media::getMediaName()
     */
    public function getMediaName() {
        return '电视剧';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Service\Media::getMarkNames()
     */
    public function getMarkNames() {
        return array(
            self::MARK_UNMARKED     => '取消标记',
            self::MARK_INTERESTED   => '想看',
            self::MARK_WATCHING     => '在看',
            self::MARK_WATCHED      => '已看',
            self::MARK_IGNORED      => '不喜欢',
        );
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Service\Media::getMediaModelName()
     */
    protected function getMediaModelName() {
        return 'X\\Module\\Lunome\\Model\\TvModel';
    }
    
    const MARK_UNMARKED     = 0;
    const MARK_INTERESTED   = 1;
    const MARK_WATCHING     = 2;
    const MARK_WATCHED      = 3;
    const MARK_IGNORED      = 4;
}