<?php
namespace X\Core\Test\Service;

/**
 * 
 */
use X\Core\X;
use X\Core\Util\TestCase;
use X\Core\Test\Fixture\Service\TestService;
use X\Core\Service\Manager;
use X\Core\Util\Exception;

/**
 * 
 */
class XServiceTest extends TestCase {
    /**
     * 
     */
    public function test_getPath() {
        $service = TestService::getService();
        $servicePath = $service->getPath();
        $exceptPath = X::system()->getPath('Core/Test/Fixture/Service');
        $this->assertSame($servicePath, $exceptPath);
        
        $servicePath = $service->getPath('Test');
        $exceptPath = X::system()->getPath('Core/Test/Fixture/Service/Test');
        $this->assertSame($servicePath, $exceptPath);
        $service->destroy();
    }
    
    /**
     * 
     */
    public function test_getConfiguration() {
        $service = TestService::getService();
        $configuration = $service->getConfiguration();
        $this->assertSame('value1', $configuration['k1']);
        $service->stop();
        $service->destroy();
    }
    
    /**
     * 
     */
    public function test_Service() {
        /* setup */
        $manager = X::system()->getServiceManager();
        $oldConfiguration = $manager->getConfiguration()->toArray();
        $this->cleanTheConfiguration($manager);
        
        $manager->register('X\\Core\\Test\\Fixture\\Service\\TestService');
        /* @var $service \X\Core\Test\Fixture\Service\TestService */
        $service = $manager->get('TestService');
        $this->assertSame('', $service->getDescription());
        $this->assertSame('TestService', $service->getPettyName());
        
        try {
            $service->enable();
            $this->fail('It should throw an exception if try to enable a service which has not been installed.');
        } catch ( Exception $e ) {}
        
        $service->install();
        $this->assertTrue($service->isInstalled());
        
        $this->assertFalse($service->isEnabled());
        $service->enable();
        $this->assertTrue($service->isEnabled());
        
        $this->assertTrue($service->isLazyLoadEnabled());
        $service->disableLazyLoad();
        $this->assertFalse($service->isLazyLoadEnabled());
        
        $service->enableLazyLoad();
        $this->assertTrue($service->isLazyLoadEnabled());
        
        $service->disable();
        $this->assertFalse($service->isEnabled());
        
        $service->uninstall();
        $this->assertFalse($service->isInstalled());
        
        /* clean up. */
        $this->cleanTheConfiguration($manager);
        $manager->getConfiguration()->merge($oldConfiguration);
        $manager->getConfiguration()->save();
        $manager->stop();
        $manager->destroy();
        $manager = null;
    }
    
    /**
     * @param Manager $manager
     */
    private function cleanTheConfiguration( Manager $manager ) {
        $configuration = $manager->getConfiguration()->toArray();
        foreach ( $configuration as $key => $config ) {
            $manager->getConfiguration()->remove($key);
        }
        $manager->getConfiguration()->save();
    }
}