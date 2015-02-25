<?php
namespace X\Service\XRequest\Test\Core;

/**
 * 
 */
use X\Core\Util\TestCase;
use X\Service\XRequest\Service;
use X\Service\XRequest\Core\Exception;

/**
 *
 */
class RouterManagerTest extends TestCase {
    /**
     * 
     */
    public function test_RouterManager() {
        /* @var $manager \X\Service\XRequest\Core\RouterManager */
        Service::getService()->start();
        $manager = Service::getService()->getRouterManager();
        
        $manager->register('test', '', '');
        try {
            $manager->register('test', '', '');
            $this->fail('An exception should be throwed if try to register a router twice with same name.');
        } catch ( Exception $e ){}
        
        try {
            $manager->unregister('non-exists');
            $this->fail('An exception should be throwed if try to unregister a non exists router.');
        } catch ( Exception $e ){}
        
        try {
            $manager->createURL('non-exists-router');
            $this->fail('An exception should be throwed if try to create a url by a non exists router.');
        } catch ( Exception $e ) {}
        
        $manager->register('6+x=x', '6+{$parm2:/.*?/}={$parm3:/.*?/}', '{$parm2}:{$parm3}');
        $manager->register('6-x=3', '6-{$parm2:/.*?/}=3', '{$parm2}');
        $this->assertSame('3', $manager->route('6-3=3'));
        
        try {
            $manager->route('non-exists-router');
            $this->fail('An exception should be throwed if try to route a non exists router.');
        } catch ( Exception $e ){}
        
        $manager->unregister('6+x=x');
        $manager->unregister('6-x=3');
        $manager->unregister('test');
        Service::getService()->stop();
        Service::getService()->destroy();
    }
}