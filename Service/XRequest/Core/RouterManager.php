<?php
namespace X\Service\XRequest\Core;
use X\Service\XRequest\Service;
class RouterManager {
    /**
     * @var Service
     */
    private $requestService = null;
    
    /**
     * @var \X\Core\Util\ConfigurationFile
     */
    private $serviceConfiguration = null;
    
    /**
     * @param Service $service
     */
    public function __construct( Service $service ) {
        $this->requestService = $service;
        $this->serviceConfiguration = $service->getConfiguration();
    }
    
    /**
     * @param unknown $name
     * @param unknown $source
     * @param unknown $destination
     */
    public function register( $name, $source, $destination ) {
        if ( $this->has($name) ) {
            throw new Exception('Router "'.$name.'" already exists.');
        }
        $router = array('source'=>$source, 'destination'=>$destination);
        $this->serviceConfiguration['rules'][$name] = $router;
        $this->serviceConfiguration->save();
    }
    
    /**
     * @param unknown $name
     * @return boolean
     */
    public function has( $name ) {
        return isset($this->serviceConfiguration['rules'][$name]);
    }
    
    /**
     * @param unknown $name
     */
    public function unregister( $name ){
        if ( !$this->has($name) ) {
            throw new Exception('Router "'.$name.'" does not exists.');
        }
        unset($this->serviceConfiguration['rules'][$name]);
        $this->serviceConfiguration->save();
    }
    
    /**
     * @param unknown $url
     * @throws Exception
     * @return mixed
     */
    public function route($url) {
        foreach ( $this->serviceConfiguration['rules'] as $name => $rule ) {
            $requestPattern = $rule['source'];
            preg_match_all('/\\{\\$(.*?):(.*?)\\}/', $rule['destination'], $parameters);
            foreach ( $parameters[0] as $index => $matchedParameter ) {
                $paramPattern = $parameters[2][$index];
                $paramPattern = sprintf('(%s)', substr($paramPattern, 1, strlen($paramPattern)-2));
                $requestPattern = str_replace($matchedParameter, $paramPattern, $requestPattern);
            }
        
            $requestPattern = sprintf('/^%s$/', $requestPattern);
            if ( 0 == preg_match($requestPattern, $url, $matchedParametersFromUrl) ) {
                continue;
            }
        
            $targetUrl = $rule['destination'];
            array_shift($matchedParametersFromUrl);
            foreach ( $parameters[1] as $index => $paramName ) {
                $paramName = sprintf('{$%s}', $paramName);
                $targetUrl = str_replace($paramName, $matchedParametersFromUrl[$index], $targetUrl);
            }
            
            return $targetUrl;
        }
        
        throw new Exception('Unable to route url "'.$url.'".');
    }
    
    /**
     * @param unknown $name
     * @param unknown $parameters
     * @throws Exception
     * @return string
     */
    public function createURL( $name, $parameters=array() ) {
        if ( !$this->has($name) ) {
            throw new Exception('Can not find router "'.$name.'".');
        }
        
        $query = $this->serviceConfiguration['rules'][$name]['source'];
        $query = str_replace('\\', '', $query);
        preg_match_all('/\\{\\$(.*?):(.*?)\\}/', $query, $urlParameters);
        foreach ( $urlParameters[0] as $index => $matchedParameter ) {
            $paramName = $urlParameters[1][$index];
            $query = str_replace($matchedParameter, $parameters[$paramName], $query);
        }
        
        $scheme = $this->requestService->getRequest()->getScheme();
        $host = $this->requestService->getRequest()->getHost();
        $url = sprintf('%s://%s/%s', $scheme, $host, $query);
        return $url;
    }
}