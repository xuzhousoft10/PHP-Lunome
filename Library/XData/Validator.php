<?php
namespace X\Library\XData;
class Validator {
    public static function isInteger( $value ) {
        return is_numeric($value) && is_int($value+0);
    }
}