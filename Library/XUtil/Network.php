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
    public static function IpInfo( $ip, $country=null ) {
        if ( '127.0.0.1' === $ip ) {
            return array('country'=>'','province'=>'', 'city'=>'', 'isp'=>'');
        } else if ( null === $country ) {
            return self::getIpInfoFromGlobal($ip);
        } else {
            $country = ucfirst(strtolower($country));
            $handler = sprintf('getIpInfoFrom%s', $country);
            return call_user_func_array(array('\\X\\Library\\XUtil\\Network', $handler), array($ip));
        }
    }
    
    /**
     * 
     * @return string
     */
    public static function getClientIP() {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $serverValues = array(
            'HTTP_CLIENT_IP', 
            'HTTP_X_FORWARDED_FOR', 
            'HTTP_X_FORWARDED', 
            'HTTP_FORWARDED_FOR', 
            'HTTP_FORWARDED'
        );
        
        foreach ( $serverValues as $serverValue ) {
            if ( !isset($_SERVER[$serverValue]) ) { continue; }
            if ( empty($_SERVER[$serverValue]) ) { continue; }
            if ( '127.0.0.1' === $_SERVER[$serverValue] ) { continue; }
            $ipaddress = $_SERVER[$serverValue];
        }

        return $ipaddress;
    }
    
    /**
     * 
     * @param unknown $ip
     * @return mixed
     */
    protected static function getIpInfoFromGlobal( $ip ) {
        $response = file_get_contents(sprintf('http://ipinfo.io/%s/json', $ip));
        $IPInfo = json_decode($IPInfo, true);
        return $IPInfo;
    }
    
    /**
     * 
     * @param unknown $ip
     */
    protected static function getIpInfoFromChina( $ip ) {
        $response = file_get_contents(sprintf('http://ip.taobao.com/service/getIpInfo.php?ip=%s', $ip));
        $IPInfo = json_decode($response, true);
        $usefulInfo = array();
        $usefulInfo['country']  = $IPInfo['data']['country'];
        $usefulInfo['province'] = $IPInfo['data']['region'];
        $usefulInfo['city']     = $IPInfo['data']['city'];
        $usefulInfo['isp']      = $IPInfo['data']['isp'];
        return $usefulInfo;
    }
}