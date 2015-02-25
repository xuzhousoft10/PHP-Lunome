<?php
namespace X\Core\Event;

/**
 * 
 */
use X\Core\X;
use X\Core\Util\Exception;
use X\Core\Util\Manager as UtilManager;
use X\Core\Util\XUtil;

/**
 * 
 */
class Manager extends UtilManager {
    /**
     * @var array
     */
    private $registeredHandlers = array();
    
    /**
     * @var array
     */
    private $eventHandlers = array();
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\Manager::start()
     */
    public function start() {
        parent::start();
        
        $this->registeredHandlers = require $this->getEventRegistrationFilePath();
        foreach ( $this->registeredHandlers as $handlerClass ) {
            $this->loadEventHandler($handlerClass);
        }
    }
    
    /**
     * @param string $handlerClassName
     * @return void
     */
    public function register( $handlerClassName ) {
        if ( in_array($handlerClassName, $this->registeredHandlers) ) {
            throw new Exception('"'.$handlerClassName.'" already been registered as an event handler.');
        }
        
        $this->loadEventHandler($handlerClassName);
        $this->registeredHandlers[] = $handlerClassName;
        $this->updateEventRegistration();
    }
    
    /**
     * @param string $handlerClassName
     * @throws Exception
     * @return void
     */
    private function loadEventHandler( $handlerClassName ) {
        if ( !class_exists($handlerClassName) ) {
            throw new Exception('Event handler "'.$handlerClassName.'" does not exists.');
        }
        
        if ( !is_subclass_of($handlerClassName, '\\X\\Core\\Event\\Handler') ) {
            throw new Exception('Event handler "'.$handlerClassName.'" should be extends from "\\X\\Core\\Event\\Handler".');
        }
        
        /* @var $handler Handler */
        $handler = new $handlerClassName();
        foreach ( $handler->getHandledEventNames() as $eventName ) {
            if ( !isset($this->eventHandlers[$eventName]) ) {
                $this->eventHandlers[$eventName] = array();
            }
            $this->eventHandlers[$eventName][] = $handler;
        }
    }
    
    /**
     * @param string $handlerClassName
     */
    private function unloadEventHandler($handlerClassName) {
        foreach ( $this->eventHandlers as $eventName => $handlers ) {
            foreach ( $handlers as $index => $handler ) {
                if ( !($handler instanceof $handlerClassName) ) {
                    continue;
                }
                unset($this->eventHandlers[$eventName][$index]);
            }
        }
    }
    
    /**
     * @return void
     */
    private function updateEventRegistration() {
        XUtil::storeArrayToPHPFile($this->getEventRegistrationFilePath(), $this->registeredHandlers);
    }
    
    /**
     * @return string
     */
    private function getEventRegistrationFilePath() {
        return X::system()->getPath('Core/Event/Configuration/Registration.php');
    }
    
    /**
     * @param unknown $handlerClassName
     * @throws Exception
     */
    public function unregister( $handlerClassName ) {
        $handlerIndex = array_search($handlerClassName, $this->registeredHandlers);
        if ( false === $handlerIndex ) {
            throw new Exception('"'.$handlerClassName.'" is not an event handler.');
        }
        
        $this->unloadEventHandler($handlerClassName);
        unset($this->registeredHandlers[$handlerIndex]);
        $this->updateEventRegistration();
    }
    
    /**
     * @param string $eventName
     * @param mixed $param1
     * @param mixed $param2,...
     * @return array
     */
    public function trigger( $eventName ) {
        if ( !isset($this->eventHandlers[$eventName]) ) {
            return array();
        }
        
        $parameters = func_get_args();
        array_shift($parameters);
        
        $result = array();
        foreach ( $this->eventHandlers[$eventName] as $handler ) {
            /* @var $handler Handler */
            $handler->currentEventName = $eventName;
            $handler->eventParameters = $parameters;
            $result[] = $handler->execute();
        }
        return $result;
    }
}