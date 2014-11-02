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