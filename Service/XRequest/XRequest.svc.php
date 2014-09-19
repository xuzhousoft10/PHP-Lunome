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
use X\Library\XUtil\Network;

/**
 * The XRequest service
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class XRequestService extends \X\Core\Service\XService {
    /**
     * This value holds the service instace.
     *
     * @var XService
     */
    protected static $service = null;
    
    /**
     * The current request intance.
     * 
     * @var \X\Service\XRequest\Core\Request
     */
    protected $request = null;
    
    /**
     * 
     * @return \X\Service\XRequest\Core\Request
     */
    public function getRequest() {
        return $this->request;
    }
    
    /**
     * This service does nothing under cli mode.
     * 
     * @see \X\Core\Service\XService::afterStart()
     * @return void
     */
    protected function afterStart() {
        if ( X::system()->isCLI() ) {
            return;
        }
        
        X::system()->registerShortcutFunction('createURL', array($this, 'createUrl'));
        X::system()->registerShortcutFunction('createHomePageUrl', array($this, 'createHomePageUrl'));
        
        $this->request = new Request();
        $this->filterTheRequest();
        $this->routeCurrentRequest();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::beforeStop()
     */
    protected function beforeStop() {
        if ( 'on' == $this->configuration['RecordRequest']['status'] ) {
            $this->recordRequest();
        }
    }
    
    /**
     * Filter the request and handle the request if it's in the blacklist.
     * 
     * @return void
     */
    protected function filterTheRequest() {
        $userAgent = $this->request->getUserAgent();
        foreach ($this->configuration['Blacklist'] as $item ) {
            if ( preg_match(sprintf('/%s/', $item), $userAgent) ) {
                $this->handleFilterFailed();
            }
        }
    }
    
    /**
     * 
     */
    protected function handleFilterFailed() {
        $handler = $this->configuration['UserAgentFilterFailHandler'];
        if ( is_callable($handler) ) {
            call_user_func($handler);
        } else if ( is_file($handler) ) {
            require $handler;
        } else if ( 'exception' == $handler ) {
            throw new Exception('The client user agent is in the blanklist.');
        } else {
            echo $handler;
            X::system()->stop();
        }
    }
    
    /**
     * Route current request and setup parameters for system.
     * 
     * @return void
     */
    protected function routeCurrentRequest() {
        $url = ('/' == $this->request->URI) ? '' : substr($this->request->URI, 1);
        $parameters = $this->routeURL($url);
        if ( false === $parameters ) {
            $this->handleErrorUnmatched();
        }
        $_GET = $parameters;
    }
    
    /* Rule index */
    const ROUTE_RULE_INDEX_ORIGINAL = 0;
    const ROUTE_RULE_INDEX_TARGET   = 1;
    
    /**
     * Route the request url to normal url.
     *
     * @param string $url The url to route
     * @throws Exception
     * @return array|false
     */
    public function routeURL( $url ) {
        foreach ( $this->configuration['Rules'] as $name => $rule ) {
            $requestPattern = $rule[self::ROUTE_RULE_INDEX_ORIGINAL];
            preg_match_all('/\\{\\$(.*?):(.*?)\\}/', $rule[self::ROUTE_RULE_INDEX_ORIGINAL], $parameters);
            foreach ( $parameters[0] as $index => $matchedParameter ) {
                $paramPattern = $parameters[2][$index];
                $paramPattern = sprintf('(%s)', substr($paramPattern, 1, strlen($paramPattern)-2));
                $requestPattern = str_replace($matchedParameter, $paramPattern, $requestPattern);
            }
    
            $requestPattern = sprintf('/^%s$/', $requestPattern);
            if ( 0 == preg_match($requestPattern, $url, $matchedParametersFromUrl) ) {
                continue;
            }
    
            $targetUrl = $rule[self::ROUTE_RULE_INDEX_TARGET];
            array_shift($matchedParametersFromUrl);
            foreach ( $parameters[1] as $index => $paramName ) {
                $paramName = sprintf('{$%s}', $paramName);
                $targetUrl = str_replace($paramName, $matchedParametersFromUrl[$index], $targetUrl);
            }
    
            parse_str($targetUrl, $newParameters);
            return $newParameters;
        }
    
        return false ;
    }
    
    /**
     * Record current request.
     * 
     * @return void
     */
    protected function recordRequest() {
        $recorder = $this->getRecorder($this->config['RecordRequest']);
        
        $request = array();
        $request['url'] = $this->request->URI;
        $request['ip'] = $this->request->clienIP;
        $request['location'] = $this->getIPLocation($this->request->clienIP);
        $request['date'] = date('Y-m-d H:i:s', time());
        $request['time'] = $this->getRequest()->getTimeSpend();
        $recorder->record($request);
    }
    
    /**
     * Get location information from ip address.
     * 
     * @param string $ip
     * @return string
     */
    protected function getIPLocation( $ip ) {
        $default = array('org'=>'', 'city'=>'','region'=>'','country'=>'');
        
        $IPInfo = Network::IpInfo($ip);
        $IPInfo = array_merge($default, $IPInfo);
        
        $location = array();
        foreach ( $default as $name => $value ) {
            if ( empty( $IPInfo[$name] ) ) {
                continue;
            }
            $location[] = $IPInfo[$name];
        }
        $location = implode(', ', $location);
        return $location;
    }
    
    /**
     * Get the instance of request recorder.
     * 
     * @return object
     */
    protected function getRecorder( $config ) {
        $handlerType = $config['handler'];
        $handlerName = sprintf('\\X\\Service\\XRequest\\Record\\Driver\\%sHandler', $handlerType);
        $handlerPath = sprintf('%s/core/recorder/%s.php', dirname(__FILE__), $handlerType);
        
        if ( !class_exists($handlerName, false) ) {
            require $handlerPath;
        }
        
        if ( !class_exists($handlerName, false) ) {
            throw new Exception(sprintf('Can not find record handler "%s"', $handlerName));
        }
        
        $handler = new $handlerName($config);
        return $handler;
    }
    
    /**
     * Handle the error on not rules matched.
     * This function would exist the application.
     * 
     * @return void
     */
    protected function handleErrorUnmatched() {
        $handler = $this->configuration['Error']['unmatched'];
        if ( 'exception' === $handler ) {
            throw new \Exception('No rules matched.');
        } else if ( is_file($handler) ) {
            require_once $handler;
        } else if ( filter_var($handler, FILTER_VALIDATE_URL) ) {
            header(sprintf('Location: %s', $handler));
        } else {
            echo $handler;
        }
        X::system()->stop();
    }
    
    /**
     * Create url by given name and parameters.
     * 
     * @param string $name The name of url rule.
     * @param array $parameters The parameters to the rule.
     * @throws Exception
     * @return string
     */
    public function createUrl( $name, $parameters=array() ) {
        if ( !isset($this->configuration['Rules'][$name]) ) {
            throw new Exception('Can not find router.');
        }
        
        $query = $this->configuration['Rules'][$name][self::ROUTE_RULE_INDEX_ORIGINAL];
        $query = str_replace('\\', '', $query);
        preg_match_all('/\\{\\$(.*?):(.*?)\\}/', $query, $urlParameters);
        foreach ( $urlParameters[0] as $index => $matchedParameter ) {
            $paramName = $urlParameters[1][$index];
            $query = str_replace($matchedParameter, $parameters[$paramName], $query);
        }
        
        $scheme = $this->request->scheme;
        $host = $this->request->host;
        $url = sprintf('%s://%s/%s', $scheme, $host, $query);
        return $url;
    }
    
    /**
     * Create url for home page.
     * @param unknown $parameters
     * @return string
     */
    public function createHomePageUrl( $parameters = array() ) {
        return $this->createUrl($this->configuration['HomePage'], $parameters);
    }
}

return __NAMESPACE__;