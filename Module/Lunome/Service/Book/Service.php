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
     * This value holds the service instace.
     *
     * @var XService
     */
    protected static $service;
    
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