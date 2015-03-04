<?php
/**
 * The basic model class
 */
namespace X\Util\Model;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\ActiveRecord\XActiveRecord;

/**
 * The basic model class
 */
abstract class Basic extends XActiveRecord {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::init()
     */
    protected function init() {
        parent::init();
        if ( $this->has('id') ) {
            $this->getAttribute('id')->setValueBuilder(array($this, 'buildId'));
        }
    }
    
    /**
     * @param unknown $record
     */
    public function buildId( $record ) {
        return$this->generateUUID();
    }
    
    /**
     * @return string
     */
    protected function generateUUID () {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0x0fff ) | 0x4000,
                mt_rand( 0, 0x3fff ) | 0x8000,
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ));
    }
}