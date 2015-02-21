<?php
namespace X\Core\Test\Module;
/**
 * 
 */
use X\Core\X;
use X\Core\Util\XUtil;
use X\Core\Util\TestCase;
use X\Core\Util\Exception;

/**
 * 
 */
class ManagerTest extends TestCase {
    /**
     * 
     */
    public function test_Manager() {
        $manager = X::system()->getModuleManager();
        $oldConfiguration = $manager->getConfiguration()->toArray();
        $this->cleanTheConfiguration($manager);
        
        /* create a test module */
        $testModuleName = 'Test';
        $modulePath = X::system()->getPath('Module/Test');
        mkdir($modulePath);
        $moduleClassContent = array();
        $moduleClassContent[0] = "<?php";
        $moduleClassContent[1] = "namespace X\\Module\\Test;";
        $moduleClassContent[2] = "class Module extends \\X\\Core\\Module\\XModule {";
        $moduleClassContent[3] = "    public function run(\$parameters = array()) {";
        $moduleClassContent[4] = "        return 'test module ran';";
        $moduleClassContent[5] = "    }";
        $moduleClassContent[6] = "}";
        $moduleClassContentString = implode("\n", $moduleClassContent);
        file_put_contents(X::system()->getPath('Module/Test/Module.php'), $moduleClassContentString);
        
        /* test register new module. */
        $manager->start();
        $manager->register($testModuleName);
        try {
            $manager->register($testModuleName);
            $this->fail('An exception should be throwed if try to register a module twice.');
        } catch ( Exception $e ) {}
        try {
            $manager->register('non-exists-module-class');
            $this->fail('An exception should be throwed if try to register a non exists module.');
        } catch ( Exception $e ) {}
        $this->assertSame(array('Test'), $manager->getList());
        $this->assertTrue($manager->has($testModuleName));
        
        /* test the module and get() method of the manager. */
        try {
            $manager->get('non-exists-module');
            $this->fail('An exception should be throwed if try to get a non exists module.');
        } catch ( Exception $e ) {}
        $module = $manager->get($testModuleName);
        
        $module->setAsDefault();
        $this->assertTrue($module->isDefaultModule());
        $module->unsetAsDefault();
        $this->assertFalse($module->isDefaultModule());
        
        $xpath = X::system()->getPath('Module/Test');
        $this->assertSame($xpath, $module->getPath());
        $xpath = X::system()->getPath('Module/Test/Test');
        $this->assertSame($xpath, $module->getPath('Test'));
        
        mkdir(X::system()->getPath('Module/Test/Configuration'));
        $configContent = "<?php return array('k1'=>'value1');";
        file_put_contents(X::system()->getPath('Module/Test/Configuration/Main.php'), $configContent);
        $this->assertSame('value1', $module->getConfiguration()->get('k1'));
        
        $module->enable();
        $this->assertTrue($module->isEnabled());
        $module->disable();
        $this->assertFalse($module->isEnabled());
        $manager->stop();
        
        /* test run() */
        $module->setAsDefault();
        $manager->start();
        $result = $manager->run();
        $this->assertSame('test module ran', $result);
        try {
            $manager->run('non-exists');
            $this->fail('An excpetion should be throwed if try to run an non exists module.');
        } catch ( Exception $e ) {}
        try {
            $manager->unregister('non-exists-module');
            $this->fail('An exception should be throwed if try to unregister a non exists module.');
        } catch ( Exception $e ) {}
        
        $module = $manager->get($testModuleName)->unsetAsDefault();
        $manager->stop();
        $manager->start();
        try {
            $manager->run();
            $this->fail('An exception should be throwed if unable to find a module to run.');
        } catch ( Exception $e ){}
        $manager->stop();
        
        $manager->start();
        $manager->configuration['NonExistsModule']=array();
        try {
            $manager->run('NonExistsModule');
            $this->fail('An exception should be throwed if module class name is not right.');
        } catch ( Exception $e ){}
        $manager->stop();
        XUtil::deleteFile($modulePath);
        
        /* test wrong parent class */
        $manager->start();
        $modulePath = X::system()->getPath('Module/TestWrongParentClass');
        mkdir($modulePath);
        $moduleClassContent = array();
        $moduleClassContent[0] = "<?php";
        $moduleClassContent[1] = "namespace X\\Module\\TestWrongParentClass;";
        $moduleClassContent[2] = "class Module {";
        $moduleClassContent[3] = "    public function run(\$parameters = array()) {";
        $moduleClassContent[4] = "        return 'test module ran';";
        $moduleClassContent[5] = "    }";
        $moduleClassContent[6] = "}";
        $moduleClassContentString = implode("\n", $moduleClassContent);
        file_put_contents(X::system()->getPath('Module/TestWrongParentClass/Module.php'), $moduleClassContentString);
        $manager->register('TestWrongParentClass');
        try {
            $manager->get('TestWrongParentClass');
            $this->fail('An exception should be throwed if try to get a wrong parent class module.');
        } catch ( Exception $e ){}
        $manager->unregister('TestWrongParentClass');
        $manager->unregister($testModuleName);
        $manager->stop();
        XUtil::deleteFile($modulePath);
        
        /* restore the test enviriment. */
        $this->cleanTheConfiguration($manager);
        $manager->getConfiguration()->merge($oldConfiguration);
        $manager->getConfiguration()->save();
    }
    
    /**
     * @param Manager $manager
     */
    private function cleanTheConfiguration( $manager ) {
        $configuration = $manager->getConfiguration()->toArray();
        foreach ( $configuration as $key => $config ) {
            $manager->getConfiguration()->remove($key);
        }
        $manager->getConfiguration()->save();
    }
}