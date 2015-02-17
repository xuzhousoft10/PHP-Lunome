<?php
namespace X\Core\Test\Util;

/**
 * 
 */
use X\Core\X;
use X\Core\Util\TestCase;
use X\Core\Test\Fixture\Util\Manager;

/**
 * Manager test case.
 */
class ManagerTest extends TestCase {
    /**
     * 
     * @return Ambigous <unknown, NULL>
     */
    public function test_getManager() {
        $manager = Manager::getManager();
        $this->assertInstanceOf('X\\Core\\Test\\Fixture\\Util\\Manager', $manager);
        $this->assertTrue($manager->isInited);
        $this->assertSame(Manager::STATUS_STOPED, $manager->getStatus());
        Manager::getManager();
        $this->assertSame(1, Manager::$initedCount);
        $manager->destroy();
        
        Manager::getManager();
        $this->assertSame(2, Manager::$initedCount);
    }
    
    /**
     * 
     */
    public function test_getConfiguration() {
        $manager = Manager::getManager();
        $this->assertNull($manager->getConfiguration());
        $manager->destroy();
        
        $path = X::system()->getPath('Core/Test/Fixture/Util/ConfigurationFileValidatedConfiguration.php');
        $manager = Manager::getManager();
        $manager->configurationPath = $path;
        $configuration = $manager->getConfiguration();
        $this->assertInstanceOf('\\X\\Core\\Util\\ConfigurationFile', $configuration);
        $this->assertSame('value1', $configuration['k1']);
    }
}

