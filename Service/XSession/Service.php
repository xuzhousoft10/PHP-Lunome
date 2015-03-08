<?php
/**
 * Namespace defination.
 */
namespace X\Service\XSession;

/**
 * The session service
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class Service extends \X\Core\Service\XService {
    /**
     * @var unknown
     */
    protected static $serviceName = 'XSession';
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::start()
     */
    public function start() {
        parent::start();
        session_start();
    }
    
    /**
     * 
     */
    public function close() {
        session_write_close();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::afterStart()
     */
    protected function afterStart() {
        $this->setupSessionHandler();
        session_start();
    }
    
    /**
     * Setup the session handler for php.
     * 
     * @return void
     */
    protected function setupSessionHandler() {
        if ( 'none' == $this->configuration['handler']['type'] ) {
            return;
        }
        
        $sessionHandler = $this->getSessionHandler( $this->configuration['handler'] );
        if (version_compare(phpversion(), '5.4.0', '<')) { 
            session_set_save_handler(
                array($sessionHandler, 'open'), 
                array($sessionHandler, 'close'), 
                array($sessionHandler, 'read'),
                array($sessionHandler, 'write'),
                array($sessionHandler, 'destroy'),
                array($sessionHandler, 'gc'));
        } else {
            session_set_save_handler($sessionHandler);
        }
    }
    
    /**
     * Get session handler instance by configuration.
     * 
     * @param array $config
     * @throws Exception Throw the exception if the handler can not be found.
     * @return \X\Service\XSession\XSessionHandlerDriverBase
     */
    protected function getSessionHandler( $config ) {
        $ds = DIRECTORY_SEPARATOR;
        $handler = sprintf('\\X\\Service\\XSession\\Core\\Driver\\Handler%s', ucfirst($config['type']));
        $sessionHandler = new $handler($config);
        return $sessionHandler;
    }
}
return __NAMESPACE__;