<?php
namespace X\Library\XData;
class Validator {
    /**
     * @param unknown $value
     * @return boolean
     */
    public static function isInteger( $value ) {
        return is_numeric($value) && is_int($value+0);
    }
    
    /**
     * @param unknown $value
     * @return boolean
     */
    public static function isUUIDString( $value ) {
        $pattern = '/[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}/';
        return (36 === strlen($value)) && preg_match($pattern, strtoupper($value));
    }
}