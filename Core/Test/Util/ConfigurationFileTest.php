<?php
/**
 * 
 */
use X\Core\X;
use X\Core\Util\TestCase;
use X\Core\Util\Exception;
use X\Core\Util\ConfigurationFile;

/**
 * ConfigurationFile test case.
 */
class ConfigurationFileTest extends TestCase {
    /**
     * Tests ConfigurationFile->__construct()
     */
    public function test__construct() {
        $path = X::system()->getPath('Core/Test/Fixture/Util/ConfigurationFileValidatedConfiguration.php');
        $configuration = new ConfigurationFile($path);
        $this->assertSame(3, $configuration->getLength());
        $this->assertSame('value3', $configuration['k3']['k3-1']['k3-1-1']);
        
        try { 
            $path = X::system()->getPath('Core/Test/Fixture/Util/ConfigurationFileInvalidatedConfiguration.php');
            $configuration = new ConfigurationFile($path);
            $this->fail('Non-array configuration file should throw an exception.');
        } catch (Exception $e) {}
        
        try {
            $path = X::system()->getPath('Core/Test/Fixture/Util/');
            $configuration = new ConfigurationFile($path);
            $this->fail('configuration file could not be a directory');
        } catch (Exception $e) {}
        
        $configuration = new ConfigurationFile('non-exists-path');
        $this->assertSame(0, $configuration->getLength());
    }
    
    /**
     * Tests ConfigurationFile->save()
     */
    public function testSave() {
        $path = dirname(__FILE__).DIRECTORY_SEPARATOR.'ConfigurationFileTestConfigFile.php';
        $configuration = new ConfigurationFile($path);
        $configuration->save();
        $this->assertSame($configuration->toArray(), require $path);
        unlink($path);
        
        $configuration = new ConfigurationFile($path);
        $configuration['test'] = 'value1';
        $configuration->save();
        $this->assertSame($configuration->toArray(), require $path);
        unlink($path);
    }
}

