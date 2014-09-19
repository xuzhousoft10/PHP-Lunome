<?php
/**
 * 
 */
namespace X\Library\XUtil;

/**
 * The util handlers for network.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class Network {
    /**
     * Get information about given ip address.
     * 
     * @param string $ip The ip address to get information from.
     * @return array
     */
    public static function IpInfo( $ip ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf('http://ipinfo.io/%s/json', $ip));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $IPInfo = curl_exec($ch);
        curl_close($ch);
        $IPInfo = json_decode($IPInfo, true);
        return $IPInfo;
    }
}