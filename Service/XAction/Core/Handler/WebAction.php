<?php
/**
 * Namespace defination
 */
namespace X\Service\XAction\Core\Handler;

/**
 * The Web action class.
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
abstract class WebAction extends \X\Service\XAction\Core\Action {
    /**
     * Jump to target url and exit the script.
     * 
     * @param string $url The target url to jump to.
     * @param array  $parms The parameters to that url
     */
    public function gotoURL( $url, $parms=null ) {
        ob_start();
        ob_end_clean();
        
        $urlInfo = parse_url($url);
        $parmConnector = (isset($urlInfo['query'])) ? '&' : '?';
        
        if ( !is_null($parms) ) {
            $url = sprintf('%s%s%s', $url, $parmConnector, http_build_query($parms));
        }
        
        header(sprintf('Location: %s', $url));
        exit();
    }
    
    /**
     * The handler of 404 page.
     * 
     * @var string|callable
     */
    protected $E404Content = '404 NOT FOUND';
    
    /**
     * Throw 404 error and exit the script.
     * 
     * You can custome the content of 404 page by setting WebAction->E404Content
     * it can be a callable function, a file name or simple string.
     */
    public function throw404() {
        ob_start();
        ob_end_clean();
        
        header('HTTP/1.0 404 NOT FOUND');
        if ( is_callable($this->E404Content) ) {
            call_user_func_array($this->E404Content, array());
        } else if ( is_file($this->E404Content) ) {
            if ( strripos($this->E404Content,'.php') === strlen($this->E404Content)-4 ) {
                require $this->E404Content;
            } else {
                echo file_get_contents($this->E404Content);
            }
        } else {
            echo $this->E404Content;
        }
        
        exit();
    }
}