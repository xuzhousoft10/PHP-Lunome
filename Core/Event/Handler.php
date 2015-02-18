<?php
namespace X\Core\Event;

/**
 * 
 */
abstract class Handler {
    /**
     * @var string
     */
    public $currentEventName = null;
    
    /**
     * @var array
     */
    public $eventParameters = array();
    
    /**
     * @return array
     */
    abstract public function getHandledEventNames();
    
    /**
     * @return void
     */
    abstract public function execute();
}