<?php
/**
 * 
 */
namespace X\Library\XMath;

/**
 * The Number class to handle all the number stuff.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class Number {
    /**
     * Get the numbers aroung the target number.
     * 
     * @param integer $number The target numbe to get around from.
     * @param integer $size The number of around numbers.
     * @param integer $min The min number that around number start from.
     * @param integer $max The max number that around number can not over with.
     * 
     * @return integer[]
     */
    public static function getRound($number, $size, $min, $max) {
        $items = array();
        if ( $max < $size ) {
            for ( $i=$min; $i<=$max; $i++ ) {
                $items[] = $i;
            }
        } else {
            $halfLength = intval($size/2);
            $number = (0 < $number-$halfLength) ? $number-$halfLength : $min;
            if ( $number + $size > $max ) {
                $number = $max - $size + 1;
            }
            if ( 0 > $number ) {
                $number = 0;
            }
        
            for ( $i=0; $i<$size && $number < $max; $i++ ) {
                $items[] = $number;
                $number++;
            }
        }
        return $items;
    }
}