<?php
/**
 *
 */
namespace X\Library\XData;

class XDateInterval extends \DateInterval {
    public static function getReadableFromNow( $time ) {
        $time = new \DateTime($time);
        $now = new \DateTime('now');
        $diff = $now->diff($time);
        
        $units = array('y', 'm', 'd', 'h', 'i', 's');
        foreach ( $units as $unit ) {
            if ( 0 !== $diff->$unit ) {
                break;
            }
        }
        
        return array('time'=>$diff->$unit, 'unit'=>$unit);
    }
}