<?php
/**
 * 
 */
namespace X\Core\Test;

/**
 * 
 */
use X\Core\X;

/**
 * 
 */
class X_Test extends \PHPUnit_Framework_TestCase {
    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp() {
        parent::setUp ();
    }
    
    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown() {
        parent::tearDown ();
    }
    
    /**
     * Tests X::start()
     */
    public function testStart() {
        /* 如果框架已经启动， 则目前框架应该处于测试状态。 */
        $this->assertTrue(X::system()->isTesting);
    }
    
    /**
     * Tests X::stop()
     */
    public function testStop() {
        $basePath = X::system()->getPath();
        
        /* 系统退出后, 再次使用system()方法将会抛出异常。 */
        X::system()->stop(false);
        try {
            X::system();
            $this->assertTrue(false, 'X::system() should not be availabel after stop the framework.');
        } catch ( \X\Core\Exception $e ) {
            $this->assertTrue(true);
            \PHPUnitBootstrap::start();
        }
    }
    
    /**
     * Tests X::system()
     */
    public function testSystem() {
        /* 如果框架已经启动， 则目前框架应该处于测试状态。 */
        $this->assertTrue(X::system()->isTesting);
    }
    
    /**
     * Tests X->getPath()
     */
    public function testGetPath() {
        /* 如果直接调用getPath， 将会得到框架的根目录。 */
        $basePath = dirname(dirname(dirname(__FILE__)));
        $actual = X::system()->getPath();
        $this->assertSame($basePath, $actual);
        
        /* 如果参数不为空，则应该获取框架下的子目录或文件的路径。*/
        $expected = $basePath.DIRECTORY_SEPARATOR.'Test';
        $actual = X::system()->getPath('Test');
        $this->assertSame($expected, $actual);
        
        /* 该方法不检查路径是否存在或者合法 */
        $expected = $basePath.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.'..';
        $actual = X::system()->getPath('*/..');
        $this->assertSame($expected, $actual);
    }
    
    /**
     * Tests X->getParameter()
     */
    public function testGetParameter() {
        // TODO Auto-generated X_Test->testGetParameters()
        $this->markTestIncomplete ( "getParameters test not implemented" );
        /* 由于直接调用所以这里不会有参数。 */
        $expected = array();
        $actual = X::system()->getParameters();
        $this->assertSame($expected, $actual);
        
        $basePath = X::system()->getPath();
        /* 构造模拟的参数列表。 */
        global $argv;
        $argv = array('--test=test');
        X::system()->stop(false);
        X::start($basePath);
        var_dump(X::system()->getParameters());
    }
    
    /**
     * Tests X->getParameters()
     */
    public function testGetParameters() {
        // TODO Auto-generated X_Test->testGetParameters()
        $this->markTestIncomplete ( "getParameters test not implemented" );
        
        $this->X->getParameters(/* parameters */);
    }
    
    /**
     * Tests X->setParameter()
     */
    public function testSetParameter() {
        // TODO Auto-generated X_Test->testSetParameter()
        $this->markTestIncomplete ( "setParameter test not implemented" );
        
        $this->X->setParameter(/* parameters */);
    }
    
    /**
     * Tests X->setParameters()
     */
    public function testSetParameters() {
        // TODO Auto-generated X_Test->testSetParameters()
        $this->markTestIncomplete ( "setParameters test not implemented" );
        
        $this->X->setParameters(/* parameters */);
    }
    
    /**
     * Tests X->getServiceManager()
     */
    public function testGetServiceManager() {
        // TODO Auto-generated X_Test->testGetServiceManager()
        $this->markTestIncomplete ( "getServiceManager test not implemented" );
        
        $this->X->getServiceManager(/* parameters */);
    }
    
    /**
     * Tests X->getModuleManager()
     */
    public function testGetModuleManager() {
        // TODO Auto-generated X_Test->testGetModuleManager()
        $this->markTestIncomplete ( "getModuleManager test not implemented" );
        
        $this->X->getModuleManager(/* parameters */);
    }
    
    /**
     * Tests X->getConfiguration()
     */
    public function testGetConfiguration() {
        // TODO Auto-generated X_Test->testGetConfiguration()
        $this->markTestIncomplete ( "getConfiguration test not implemented" );
        
        $this->X->getConfiguration(/* parameters */);
    }
    
    /**
     * Tests X->run()
     */
    public function testRun() {
        // TODO Auto-generated X_Test->testRun()
        $this->markTestIncomplete ( "run test not implemented" );
        
        $this->X->run(/* parameters */);
    }
    
    /**
     * Tests X->_shutdown()
     */
    public function test_shutdown() {
        // TODO Auto-generated X_Test->test_shutdown()
        $this->markTestIncomplete ( "_shutdown test not implemented" );
        
        $this->X->_shutdown(/* parameters */);
    }
    
    /**
     * Tests X->_autoloader()
     */
    public function test_autoloader() {
        // TODO Auto-generated X_Test->test_autoloader()
        $this->markTestIncomplete ( "_autoloader test not implemented" );
        
        $this->X->_autoloader(/* parameters */);
    }
    
    /**
     * Tests X->registerShortcutFunction()
     */
    public function testRegisterShortcutFunction() {
        // TODO Auto-generated X_Test->testRegisterShortcutFunction()
        $this->markTestIncomplete ( "registerShortcutFunction test not implemented" );
        
        $this->X->registerShortcutFunction(/* parameters */);
    }
    
    /**
     * Tests X->__call()
     */
    public function test__call() {
        // TODO Auto-generated X_Test->test__call()
        $this->markTestIncomplete ( "__call test not implemented" );
        
        $this->X->__call(/* parameters */);
    }
    
    /**
     * Tests X->getVersion()
     */
    public function testGetVersion() {
        // TODO Auto-generated X_Test->testGetVersion()
        $this->markTestIncomplete ( "getVersion test not implemented" );
        
        $this->X->getVersion(/* parameters */);
    }
    
    /**
     * Tests X->log()
     */
    public function testLog() {
        // TODO Auto-generated X_Test->testLog()
        $this->markTestIncomplete ( "log test not implemented" );
        
        $this->X->log(/* parameters */);
    }
    
    /**
     * Tests X->setLogger()
     */
    public function testSetLogger() {
        // TODO Auto-generated X_Test->testSetLogger()
        $this->markTestIncomplete ( "setLogger test not implemented" );
        
        $this->X->setLogger(/* parameters */);
    }
    
    /**
     * Tests X->getLogger()
     */
    public function testGetLogger() {
        // TODO Auto-generated X_Test->testGetLogger()
        $this->markTestIncomplete ( "getLogger test not implemented" );
        
        $this->X->getLogger(/* parameters */);
    }
    
    /**
     * Tests X->setLogLevel()
     */
    public function testSetLogLevel() {
        // TODO Auto-generated X_Test->testSetLogLevel()
        $this->markTestIncomplete ( "setLogLevel test not implemented" );
        
        $this->X->setLogLevel(/* parameters */);
    }
    
    /**
     * Tests X->isCLI()
     */
    public function testIsCLI() {
        // TODO Auto-generated X_Test->testIsCLI()
        $this->markTestIncomplete ( "isCLI test not implemented" );
        
        $this->X->isCLI(/* parameters */);
    }
}

