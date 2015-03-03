<?php
namespace X\Service\XAction\Test\Core\Util;

/**
 * 
 */
use X\Core\Util\TestCase;
use X\Service\XAction\Test\Fixture\Action\TestAction;
use X\Service\XAction\Core\Exception;
use X\Service\XAction\Test\Fixture\Action\TestActionNoRunAction;

/**
 * 
 */
class ActionTest extends TestCase {
    /**
     * 
     */
    public function test_action() {
        $action = new TestAction('test');
        $this->assertSame('test', $action->getGroupName());
        try {
            $action->run(array());
            $this->fail('An exception should be throwed if parameter is not right.');
        } catch ( Exception $e ) {}
        
        $action = new TestActionNoRunAction('test');
        try {
            $action->run(array());
            $this->fail('An exception should be throwed if action has no "runAction" method.');
        } catch ( Exception $e ) {}
    }
}