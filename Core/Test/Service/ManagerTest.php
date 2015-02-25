<?php
namespace X\Core\Test\Service;
/**
 * 
 */
use X\Core\X;
use X\Core\Util\TestCase;
use X\Core\Service\Manager;
use X\Core\Util\Exception;
use X\Core\Service\XService;
/**
 * 
 */
class ManagerTest extends TestCase {
    /**
     * @var \X\Core\Service\Manager
     */
    private $manager = null;
    
    /**
     * @var array
     */
    private $oldConfiguration = null;
    
    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp() {
        parent::setUp();
        $this->manager = X::system()->getServiceManager();
        $this->oldConfiguration = $this->manager->getConfiguration()->toArray();
        $this->cleanTheConfiguration($this->manager);
    }
    
    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown() {
        $this->cleanTheConfiguration($this->manager);
        $this->manager->getConfiguration()->merge($this->oldConfiguration);
        $this->manager->getConfiguration()->save();
        $this->manager->stop();
        $this->manager->destroy();
        $this->manager = null;
        $this->oldConfiguration = null;
        parent::tearDown();
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
    
    /**
     * 
     */
    public function test_Manager() {
        $testServiceName = 'TestService';
        /* start and use the manager */
        $this->manager->start();
        $this->manager->register('X\\Core\\Test\\Fixture\\Service\\TestService');
        $this->assertTrue($this->manager->has($testServiceName));
        $this->assertFalse($this->manager->isLoaded($testServiceName));
        $service = $this->manager->get($testServiceName);
        $this->assertFalse($service->isEnabled());
        $this->assertFalse($service->isInstalled());
        $this->assertTrue($this->manager->isLoaded($testServiceName));
        
        $this->manager->unload($testServiceName);
        $this->assertFalse($this->manager->isLoaded($testServiceName));
        $this->manager->unregister($testServiceName);
        $this->assertFalse($this->manager->has($testServiceName));
    }
    
    /**
     * 
     */
    public function test_unregisterNonExistsService() {
        try {
            $this->manager->unregister('non-exists-service');
            $this->fail('An exception should be throwed if try to unregister a non-exists service.');
        } catch ( Exception $e ){}
    }
    
    /**
     * 
     */
    public function test_registerInvalidatedService() {
        try {
            $this->manager->register('non-exists-class');
            $this->fail('An exception should be throwed if try to register a non-exists class as a service.');
        } catch ( Exception $e ){}
        
        try {
            $this->manager->register(get_class($this));
            $this->fail('An exception should be throwed if try to register a non-service-class as a service.');
        } catch ( Exception $e ){}
        
        $this->manager->register('X\\Core\\Test\\Fixture\\Service\\TestService');
        try {
            $this->manager->register('X\\Core\\Test\\Fixture\\Service\\TestService');
            $this->fail('An exception should be throwed if try to register a service twice.');
        } catch ( Exception $e ){}
    }
    
    /**
     * 
     */
    public function test_getList() {
        $this->assertSame(array(), $this->manager->getList());
        $this->manager->register('X\\Core\\Test\\Fixture\\Service\\TestService');
        $this->assertSame(array('TestService'), $this->manager->getList());
        $this->assertSame(1, count($this->manager->getList()));
    }
    
    /**
     * 
     */
    public function test_getNonExistsService() {
        try {
            $this->manager->get('non-exists-service');
            $this->fail('An exception should be throwed if try to get a non exists service.');
        } catch ( Exception $e ){}
    }
    
    /**
     * 
     */
    public function test_unloadInvalidateService() {
        try {
            $this->manager->unload('non-exists-service');
            $this->fail('An exception should be throwed if try to unload a non exists service.');
        } catch ( Exception $e ){}
        
        /* Nothing would happend if try to unload a non loaded service. */
        $this->manager->register('X\\Core\\Test\\Fixture\\Service\\TestService');
        $this->manager->unload('TestService');
        $this->assertFalse($this->manager->isLoaded('TestService'));
    }
    
    /**
     * 
     */
    public function test_checkIsLoadedOnANonExistsServie() {
        try {
            $this->manager->isLoaded('non-exists-service');
            $this->fail('An exception should be throwed if try to check a non exists service whether it\'s loaded.');
        } catch ( Exception $e ){}
    }
    
    /**
     * 
     */
    public function test_loadInvalidateService() {
        try {
            $this->manager->load('non-exists-service');
            $this->fail('An exception should be throwed if try to load a non exists service .');
        } catch ( Exception $e ){}
        
        $this->manager->register('X\\Core\\Test\\Fixture\\Service\\TestService');
        $this->manager->configuration['TestService']['class'] = 'non-exists-class';
        try {
            $this->manager->load('TestService');
            $this->fail('An exception should be throwed if try to load a service which handler class does not exists.');
        } catch ( Exception $e ){}
        
        $this->manager->configuration['TestService']['class'] = get_class($this);
        try {
            $this->manager->load('TestService');
            $this->fail('An exception should be throwed if try to load a service which handler class does not is a service hadnler.');
        } catch ( Exception $e ){}
    }
    
    /**
     * 
     */
    public function test_stop() {
        $this->manager->register('X\\Core\\Test\\Fixture\\Service\\TestService');
        $this->manager->get('TestService')->start();
        $this->assertSame(XService::STATUS_RUNNING, $this->manager->get('TestService')->getStatus());
        $this->manager->stop();
    }
    
    /**
     * 
     */
    public function test_start() {
        $this->manager->register('X\\Core\\Test\\Fixture\\Service\\TestService');
        $this->manager->start();
        $this->assertFalse($this->manager->isLoaded('TestService'));
        $this->manager->stop();
        
        $this->manager->configuration['TestService']['enable'] = true;
        $this->manager->configuration['TestService']['delay'] = false;
        $this->manager->start();
        $this->assertTrue($this->manager->isLoaded('TestService'));
        $this->manager->stop();
    }
    
    /**
     * 
     */
    public function test_autoStartDelayedServiceWhenUserGetService() {
        $this->manager->register('X\\Core\\Test\\Fixture\\Service\\TestService');
        $service = $this->manager->get('TestService');
        $this->assertSame(XService::STATUS_STOPPED, $service->getStatus());
        $service->install();
        $service->enable();
        $service->enableLazyLoad();
        
        $service = $this->manager->get('TestService');
        $this->assertSame(XService::STATUS_RUNNING, $service->getStatus());
    }
}