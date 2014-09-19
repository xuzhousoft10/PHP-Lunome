<?php
namespace X\Database\Test\Resources;

use X\Database\ActiveRecord\ActiveRecord;

class ActiveRecordEventTester extends ActiveRecord {
    /**
     * (non-PHPdoc)
     * @see \X\Database\ActiveRecord\ActiveRecord::getTableName()
     */
    public function getTableName() {
        
    }

    /**
     * (non-PHPdoc)
     * @see \X\Database\ActiveRecord\ActiveRecord::describe()
     */
    protected function describe() {
        return array();
    }
    
    /**
     * 
     * @param unknown $event
     * @param unknown $handler
     * @return boolean
     */
    public function hasHandlerOnEvent( $event, $handler ) {
        return false !== array_search($handler, $this->eventHandlers[$event]);
    }
    
    /**
     * 
     * @param unknown $eventName
     * @param unknown $handler
     */
    public function registerEventHandlerTester( $eventName, $handler ) {
        $this->registerEventHandler($eventName, $handler);
    }
    
    /**
     * 
     */
    protected function testEventHandlerProtected() {}
    
    /**
     * 
     */
    protected function testEventHandlerWithObject() {}
    
    /**
     * 
     * @param unknown $name
     * @param unknown $handler
     */
    public function prettyNameEventRegisterTester( $name, $handler ) {
        return call_user_func_array(array($this, $name), array($handler));
    }
    
    /**
     * 
     * @var unknown
     */
    public $executedEvents = array();
    
    /**
     * 
     */
    protected function eventHandler1() {
        $this->executedEvents[] = 'eventHandler1';
    }
    
    /**
     * 
     */
    protected function eventHandler2() {
        $this->executedEvents[] = 'eventHandler2';
    }
    
    /**
     * 
     * @param unknown $eventName
     */
    public function trigerSelfEventTester($eventName) {
        $this->triggerEvent($eventName);
    }
    
    /**
     * 
     * @param unknown $trigger
     */
    public function prettyNameTriggerTester( $trigger ) {
        call_user_func(array($this, $trigger));
    }
    
    /**
     * 
     * @var unknown
     */
    public $sigleEventExecuted = false;
    
    /**
     * 
     */
    protected function sigleEventHandler() {
        $this->sigleEventExecuted = true;
    }
}

/**
 * 
 */
function event_handler_for_test_register_event() {}