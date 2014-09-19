<?php
/**
 * Namespace defination
 */
namespace X\Service\XRequest\Core;

/**
 * Use statements
 */
use X\Core\Basic;

/**
 * Store the informations about current request.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 * 
 * @property stirng $URI
 * @property string $clienIP
 * @property string $scheme
 * @property string $host
 * @property array  $parameters
 */
class Request extends Basic {
    /**
     * 
     * @var \DateTime
     */
    protected $startAt = null;
    
    /**
     * 
     * @return number
     */
    public function getTimeSpend() {
        $now = new \DateTime('now');
        return $now->getTimestamp() - $this->startAt->getTimestamp();
    }
    
    /**
     * Initiate the request of current request.
     * 
     * @return void
     */
    public function __construct() {
        $this->startAt = new \DateTime('now');
    }
    
    /**
     * Get the requested uri.
     * 
     * @return string
     */
    public function getURI(){
        return $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Get the IP address of client
     * 
     * @return string
     */
    public function getClientIP() {
        return $_SERVER['REMOTE_ADDR'];
    }
    
    /**
     * Get request scheme of current request.
     * It would be 'http' or 'https'
     * 
     * @see http://php.net/manual/en/reserved.variables.server.php
     * @return string
     */
    public function getScheme() {
        return (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])) ? 'https' : 'http';
    }
    
    /**
     * Get host name of current request.
     * 
     * @return string
     */
    public function getHost() {
        return $_SERVER['HTTP_HOST'];
    }
    
    /**
     * Get parameters from current request.
     * 
     * @return array
     */
    public function getParameters() {
        return $_GET;
    }
    
    /**
     * The user agent
     * 
     * @return string
     */
    public function getUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'];
    }
}