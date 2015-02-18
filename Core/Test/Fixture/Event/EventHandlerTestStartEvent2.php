<?php
namespace X\Core\Test\Fixture\Event;

/**
 * 
 */
use X\Core\Event\Handler;

/**
 * 
 */
class EventHandlerTestStartEvent2 extends Handler {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Event\Handler::getHandledEventNames()
     */
    public function getHandledEventNames() {
        return array('X_CORE_TEST_START_EVENT');
    }

    /**
     * (non-PHPdoc)
     * @see \X\Core\Event\Handler::execute()
     */
    public function execute() {
        return 'X_CORE_TEST_START_EVENT_2_EXECUTED';
    }
}