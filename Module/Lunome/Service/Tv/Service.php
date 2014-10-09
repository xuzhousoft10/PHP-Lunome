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
     * This value holds the service instace.
     *
     * @var XService
     */
    protected static $service;
    
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