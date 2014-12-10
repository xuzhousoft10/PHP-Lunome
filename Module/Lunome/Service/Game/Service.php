<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\Game;

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
        return '游戏';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Service\Media::getMarkNames()
     */
    public function getMarkNames() {
        return array(
            self::MARK_UNMARKED     => '取消标记',
            self::MARK_INTERESTED   => '想玩',
            self::MARK_PLAYING      => '在玩',
            self::MARK_PLAYED       => '已玩',
            self::MARK_IGNORED      => '忽略',
        );
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Service\Markable::getMediaModelName()
     */
    protected function getMediaModelName() {
        return 'X\\Module\\Lunome\\Model\\GameModel';
    }
    
    const MARK_UNMARKED     = 0;
    const MARK_INTERESTED   = 1;
    const MARK_PLAYING      = 2;
    const MARK_PLAYED       = 3;
    const MARK_IGNORED      = 4;
}