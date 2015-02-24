<?php
/**
 * Namespace defination
 */
namespace X\Service\XRequest;

/**
 * Use statements
 */
use X\Core\X;
use X\Service\XRequest\Core\Request;
use X\Service\XRequest\Core\Exception;
use X\Service\XRequest\Core\RouterManager;

/**
 * The XRequest service
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class Service extends \X\Core\Service\XService {
    /**
     * @var string
     */
    protected static $serviceName = 'XRequest';
    
    /**
     * The current request intance.
     * @var \X\Service\XRequest\Core\Request
     */
    protected $request = null;
    
    /**
     * @var RouterManager
     */
    protected $routerManager = null;
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::start()
     */
    public function start() {
        parent::start();
        
        $this->routerManager = new RouterManager($this);
        X::system()->getShortcutManager()->register('createURL', array($this, 'createURL'));
        
        $isCliMode = 'cli' === X::system()->getEnvironment()->getName();
        $isTestMode = true === $this->getConfiguration()->get('testing', false);
        if ( $isCliMode && !$isTestMode ) {
            return;
        }
        
        $this->request = new Request();
        $this->routeCurrentRequest();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::stop()
     */
    public function stop() {
        X::system()->getShortcutManager()->unregister('createURL');
        parent::stop();
    }
    
    /**
     * @return \X\Service\XRequest\Core\RouterManager
     */
    public function getRouterManager() {
        return $this->routerManager;
    }
    
    /**
     * @return \X\Service\XRequest\Core\Request
     */
    public function getRequest() {
        return $this->request;
    }
    
    /**
     * Route current request and setup parameters for system.
     * @return void
     */
    protected function routeCurrentRequest() {
        $uri = $this->getRequest()->getURI();
        $url = ('/' == $uri) ? '' : substr($uri, 1);
        $parameters = $this->routeURL($url);
        parse_str($parameters, $parameters);
        $_GET = $parameters;
    }
    
    /**
     * Route the request url to normal url.
     * @param string $url The url to route
     * @throws Exception
     * @return array|false
     */
    public function routeURL( $url ) {
        return $this->routerManager->route($url);
    }
    
    /**
     * Create url by given name and parameters.
     * @param string $name The name of url rule.
     * @param array $parameters The parameters to the rule.
     * @throws Exception
     * @return string
     */
    public function createUrl( $name ) {
        $params = func_get_args();
        array_shift($params);
        return $this->routerManager->createURL($name, $params);
    }
}