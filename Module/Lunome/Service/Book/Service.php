<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\Book;

/**
 * Use statement
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
        return '图书';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Service\Media::getMarkNames()
     */
    public function getMarkNames() {
        return array(
            self::MARK_UNMARKED     => '取消标记',
            self::MARK_INTERESTED   => '想读',
            self::MARK_READING      => '在读',
            self::MARK_READ         => '已读',
            self::MARK_IGNORED      => '不喜欢',
        );
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Service\Markable::getMediaModelName()
     */
    protected function getMediaModelName() {
        return 'X\\Module\\Lunome\\Model\\BookModel';
    }
    
    const MARK_UNMARKED     = 0;
    const MARK_INTERESTED   = 1;
    const MARK_READING      = 2;
    const MARK_READ         = 3;
    const MARK_IGNORED      = 4;
}