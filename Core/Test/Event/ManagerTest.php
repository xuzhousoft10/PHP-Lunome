<?php
namespace X\Core\Test\Event;

/**
 * 
 */
use X\Core\Util\TestCase;
use X\Core\Event\Manager;
use X\Core\Util\Exception;

/**
 * 
 */
class ManagerTest extends TestCase {
    /**
     * 
     */
    public function test_Manager(){
        /* @var $manager \X\Core\Event\Manager */
        $manager = Manager::getManager();
        $manager->start();
        $this->assertSame(Manager::STATUS_RUNNING, $manager->getStatus());
        
        /* Register the event and trigger it. */
        $manager->register('\\X\\Core\\Test\\Fixture\\Event\\EventHandlerTestStartEvent');
        $results = $manager->trigger('X_CORE_TEST_START_EVENT');
        $this->assertSame('X_CORE_TEST_START_EVENT_EXECUTED', array_shift($results));
        $manager->stop();
        $manager->destroy();
        
        /* Once you registered the event, you do not need to register again.  */
        $manager = Manager::getManager();
        $manager->start();
        $results = $manager->trigger('X_CORE_TEST_START_EVENT');
        $this->assertSame('X_CORE_TEST_START_EVENT_EXECUTED', array_shift($results));
        $manager->stop();
        $manager->destroy();
        
        /* if you trigger a non-exists event, the result would be an empty array. */
        $manager = Manager::getManager();
        $manager->start();
        $this->assertSame(array(), $manager->trigger('NON_EXISTS_EVENT'));
        
        /* Unable to unregister a non-exists event handler */
        try {
            $manager->unregister('non-exists-handler');
            $this->fail('If unregister a non-exists handler, it should throw an exception.');
        } catch ( Exception $e ) {}
        
        /* Unable to register the event handler twice. */
        try {
            $manager->register('\\X\\Core\\Test\\Fixture\\Event\\EventHandlerTestStartEvent');
            $this->fail('It should throw an exception if try to register same event handler twice.');
        } catch ( Exception $e ) {}
        
        /* Unable to register a non-exists class as an event handler. */
        try {
            $manager->register('A-NON-EXISTS-CLASS');
            $this->fail('It should throw an exception if try to register a non-exists event handler class.');
        } catch ( Exception $e ) {}
        
        /* Unable to register a non handler class as an event handler. */
        try {
            $manager->register(get_class($this));
            $this->fail('It should throw an exception if try to register a non-handler event handler class.');
        } catch ( Exception $e ) {}
        
        try {
            $manager->register('A-NON-EXISTS-CLASS');
            $this->fail('It should throw an exception if try to register a non-exists event handler class.');
        } catch ( Exception $e ) {}
        
        /* After unregistered a event handler, it does not effect another event handler. */
        $manager->register('\\X\\Core\\Test\\Fixture\\Event\\EventHandlerTestStartEvent2');
        $results = $manager->trigger('X_CORE_TEST_START_EVENT');
        $this->assertSame(2, count($results));
        $this->assertSame('X_CORE_TEST_START_EVENT_EXECUTED', array_shift($results));
        $this->assertSame('X_CORE_TEST_START_EVENT_2_EXECUTED', array_shift($results));
        $manager->unregister('\\X\\Core\\Test\\Fixture\\Event\\EventHandlerTestStartEvent2');
        $results = $manager->trigger('X_CORE_TEST_START_EVENT');
        $this->assertSame(1, count($results));
        $this->assertSame('X_CORE_TEST_START_EVENT_EXECUTED', array_shift($results));
        
        $manager->unregister('\\X\\Core\\Test\\Fixture\\Event\\EventHandlerTestStartEvent');
        $manager->stop();
        $manager->destroy();
    }
}