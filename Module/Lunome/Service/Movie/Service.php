<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\Movie;

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
        return 'X\\Module\\Lunome\\Model\\MovieModel';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Service\Markable::getMediaMarkModelName()
     */
    protected function getMediaMarkModelName() {
        return 'X\\Module\\Lunome\\Model\\MovieUserMarkModel';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Service\Markable::getMediaPosterModelName()
     */
    protected function getMediaPosterModelName() {
        return 'X\\Module\\Lunome\\Model\\MoviePosterModel';
    }
    
    /**
     * 
     * @param unknown $name
     * @return Ambigous <number, string>
     */
    protected function convertMarkNameToMarkValue( $name ) {
        $map = array(
            self::MARK_NAME_UNMARKED    => 0,
            self::MARK_NAME_INTERESTED  => self::MARK_INTERESTED,
            self::MARK_NAME_WATCHED     => self::MARK_WATCHED,
            self::MARK_NAME_IGNORED     => self::MARK_IGNORED,
        );
        return $map[$name];
    }
    
    const MARK_INTERESTED   = 1;
    const MARK_WATCHED      = 2;
    const MARK_IGNORED      = 3;
    
    const MARK_NAME_UNMARKED    = 'unmarked';
    const MARK_NAME_INTERESTED  = 'interested';
    const MARK_NAME_WATCHED     = 'watched';
    const MARK_NAME_IGNORED     = 'ignored';
}