<?php
namespace X\Service\XAction\Test;

/**
 * 
 */
use X\Core\Util\TestCase;
use X\Service\XAction\Service;
use X\Service\XAction\Core\Exception;

/**
 *
 */
class ServiceTest extends TestCase {
    /**
     * 
     */
    public function test_service() {
        /* @var $service Service */
        $service = Service::getService();
        
        $service->start();
        $this->assertFalse($service->hasGroup('test'));
        $service->addGroup('test', '\\X\\Service\\XAction\\Test\\Fixture');
        $service->setGroupDefaultAction('test', 'testAction');
        $service->getParameter()->merge(array('parm1'=>'value1'));
        $result = $service->runGroup('test');
        $this->assertSame('value1', $result);
        
        /* The registered action would replace the acturl action. */
        $service->register('test', 'testAction', '\\X\\Service\\XAction\\Test\\Fixture\\Action\\RegisteredTestAction');
        $result = $service->runGroup('test');
        $this->assertSame('value1-registered', $result);
        $service->stop();
        $service->destroy();
    }
    
    /**
     * 
     */
    public function test_serviceWithInvalidateParameters() {
        /* @var $service Service */
        $service = Service::getService();
        
        $service->start();
        $this->assertFalse($service->hasGroup('test'));
        $service->addGroup('test', '\\X\\Service\\XAction\\Test\\Fixture');
        try {
            $service->addGroup('test', '\\X\\Service\\XAction\\Test\\Fixture');
            $this->fail('An exception should be throwed if try to add a group twice.');
        } catch ( Exception $e ){}
        
        try {
            $service->register('non-exists', 'testAction', '\\X\\Service\\XAction\\Test\\Fixture');
            $this->fail('An exception should be throwed if try to register a action to a non exists group.');
        } catch ( Exception $e ){}
        
        try {
            $service->setGroupDefaultAction('non-exists-group', 'exists-action');
            $this->fail('An exception should be throwed if try to set default action to a non exists group.');
        } catch ( Exception $e ){}
        
        try {
            $service->runGroup('non-exists-group');
            $this->fail('An exception should be throwed if try to run a non exists group.');
        } catch ( Exception $e ){}
        
        try {
            $service->runGroup('test');
            $this->fail('An exception should be throwed if unable to find action in group.');
        } catch ( Exception $e ){}
        
        try {
            $service->runAction('test', 'non-exists');
            $this->fail('An exception should be throwed if unable to find action in group.');
        } catch ( Exception $e ){}
        
        $service->register('test', 'testAction2', 'non-exists-handler');
        try {
            $service->runAction('test', 'testAction2');
            $this->fail('An exception should be throwed if registered handler does not exists and no actual action.');
        } catch ( Exception $e ){}
        
        $service->setGroupDefaultAction('test', 'testAction');
        $service->getParameter()->merge(array('parm1'=>'value1'));
        $result = $service->runGroup('test');
        $this->assertSame('value1', $result);
        $service->stop();
        $service->destroy();
    }
}