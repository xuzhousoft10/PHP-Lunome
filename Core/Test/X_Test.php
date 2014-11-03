<?php
/**
 * 
 */
namespace X\Core\Test;

/**
 * 
 */
use X\Core\X;
use X\Core\InterfaceLogger;

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
     * Tests X->getParameters()
     */
    public function testGetParameters() {
        /* 由于直接调用所以这里不会有参数。 */
        $expected = array();
        $actual = X::system()->getParameters();
        $this->assertSame($expected, $actual);
        
        $basePath = X::system()->getPath();
        /* 构造模拟的参数列表。 */
        global $argv;
        $argv = array(
            '--test=test', /* 正常 test=test */
            '--debug',     /* 正常 debug=true */
            '-ignore1=ignored1', /* 忽略 */
        );
        X::system()->stop(false);
        X::start($basePath);
        $expected = array('test'=>'test', 'debug'=>true);
        $actual = X::system()->getParameters();
        $this->assertSame($expected, $actual);
    }
    
    /**
     * Tests X->getParameter()
     */
    public function testGetParameter() {
        /* 构造模拟的参数列表。 */
        global $argv;
        $argv = array(
            '--test=test', /* 正常 test=test */
            '--debug',     /* 正常 debug=true */
            '-ignore1=ignored1', /* 忽略 */
        );
        
        $basePath = X::system()->getPath();
        X::system()->stop(false);
        X::start($basePath);
        $this->assertSame('test', X::system()->getParameter('test'));
        $this->assertSame(true, X::system()->getParameter('debug'));
        $this->assertNull(X::system()->getParameter('non-exists'));
    }
    
    /**
     * Tests X->setParameter()
     */
    public function testSetParameter() {
        $this->assertNull(X::system()->getParameter('non-exists'));
        X::system()->setParameter('non-exists', 'new value');
        $this->assertSame('new value', X::system()->getParameter('non-exists'));
        X::system()->setParameter('non-exists', 'another new value');
        $this->assertSame('another new value', X::system()->getParameter('non-exists'));
        X::system()->setParameter('non-exists', null);
    }
    
    /**
     * Tests X->setParameters()
     */
    public function testSetParameters() {
        X::system()->setParameters(array('testSetParameters-p1'=>'p1', 'testSetParameters-p2'=>'p2'));
        $this->assertSame('p1', X::system()->getParameter('testSetParameters-p1'));
        $this->assertSame('p2', X::system()->getParameter('testSetParameters-p2'));
        $this->assertNull(X::system()->getParameter('non-exists'));
    }
    
    /**
     * Tests X->getServiceManager()
     */
    public function testGetServiceManager() {
        $this->assertInstanceOf('\\X\\Core\\Service\\ServiceManagement', X::system()->getServiceManager());
    }
    
    /**
     * Tests X->getModuleManager()
     */
    public function testGetModuleManager() {
        $this->assertInstanceOf('\\X\\Core\\Module\\ModuleManagement', X::system()->getModuleManager());
    }
    
    /**
     * Tests X->getConfiguration()
     */
    public function testGetConfiguration() {
        $this->assertInstanceOf('\\X\\Core\\Util\\Configuration', X::system()->getConfiguration());
    }
    
    /**
     * Tests X->run()
     * @expectedException \X\Core\Module\Exception 
     */
    public function testRun() {
        /* 关掉所有模块，这样， 如果正常启动， 则会抛出异常说没有找到模块。 */
        X::system()->getServiceManager()->getConfiguration()->setAll(array());
        X::system()->getModuleManager()->getConfiguration()->setAll(array());
        global $argv;
        $argv = array();
        $basePath = X::system()->getPath();
        X::system()->stop(false);
        X::start($basePath);
        X::system()->run();
    }
    
    /**
     * Tests X->registerShortcutFunction()
     */
    public function testRegisterShortcutFunction() {
        X::system()->registerShortcutFunction('shortcut', function() {return 1;});
        $this->assertSame(1, X::system()->shortcut());
        
        /* 如果名称已经存在， 则旧的快捷方式将会被覆盖。 */
        X::system()->registerShortcutFunction('shortcut', function() {return 2;});
        $this->assertSame(2, X::system()->shortcut());
    }
    
    /**
     * Tests X->__call()
     * @expectedException X\Core\Exception
     */
    public function test__call() {
        X::system()->non_exists_shortcut();
    }
    
    /**
     * Tests X->getVersion()
     */
    public function testGetVersion() {
        $this->assertTrue(is_array(X::system()->getVersion()));
    }
    
    /**
     * Tests X->log()
     */
    public function testLog() {
        $logger = new PHPUnitTestLogger();
        X::system()->setLogger($logger);
        
        X::system()->setLogLevel(InterfaceLogger::LOG_LEVEL_VERBOSE);
        X::system()->log('LOG_LEVEL_VERBOSE',   'phpunit-test-case', InterfaceLogger::LOG_LEVEL_VERBOSE);
        X::system()->log('LOG_LEVEL_DEBUG',     'phpunit-test-case', InterfaceLogger::LOG_LEVEL_DEBUG);
        X::system()->log('LOG_LEVEL_INFO',      'phpunit-test-case', InterfaceLogger::LOG_LEVEL_INFO);
        X::system()->log('LOG_LEVEL_NOTICE',    'phpunit-test-case', InterfaceLogger::LOG_LEVEL_NOTICE);
        X::system()->log('LOG_LEVEL_WARNING',   'phpunit-test-case', InterfaceLogger::LOG_LEVEL_WARNING);
        X::system()->log('LOG_LEVEL_ERROR',     'phpunit-test-case', InterfaceLogger::LOG_LEVEL_ERROR);
        $this->assertContains('LOG_LEVEL_VERBOSE',  $logger->messages['phpunit-test-case']);
        $this->assertContains('LOG_LEVEL_DEBUG',    $logger->messages['phpunit-test-case']);
        $this->assertContains('LOG_LEVEL_INFO',     $logger->messages['phpunit-test-case']);
        $this->assertContains('LOG_LEVEL_NOTICE',   $logger->messages['phpunit-test-case']);
        $this->assertContains('LOG_LEVEL_WARNING',  $logger->messages['phpunit-test-case']);
        $this->assertContains('LOG_LEVEL_ERROR',    $logger->messages['phpunit-test-case']);
        
        $logger->messages = array();
        X::system()->setLogLevel(InterfaceLogger::LOG_LEVEL_DEBUG);
        X::system()->log('LOG_LEVEL_VERBOSE',   'phpunit-test-case', InterfaceLogger::LOG_LEVEL_VERBOSE);
        X::system()->log('LOG_LEVEL_DEBUG',     'phpunit-test-case', InterfaceLogger::LOG_LEVEL_DEBUG);
        X::system()->log('LOG_LEVEL_INFO',      'phpunit-test-case');
        X::system()->log('LOG_LEVEL_NOTICE',    'phpunit-test-case', InterfaceLogger::LOG_LEVEL_NOTICE);
        X::system()->log('LOG_LEVEL_WARNING',   'phpunit-test-case', InterfaceLogger::LOG_LEVEL_WARNING);
        X::system()->log('LOG_LEVEL_ERROR',     'phpunit-test-case', InterfaceLogger::LOG_LEVEL_ERROR);
        $this->assertNotContains('LOG_LEVEL_VERBOSE',  $logger->messages['phpunit-test-case']);
        $this->assertContains('LOG_LEVEL_DEBUG',    $logger->messages['phpunit-test-case']);
        $this->assertContains('LOG_LEVEL_INFO',     $logger->messages['phpunit-test-case']);
        $this->assertContains('LOG_LEVEL_NOTICE',   $logger->messages['phpunit-test-case']);
        $this->assertContains('LOG_LEVEL_WARNING',  $logger->messages['phpunit-test-case']);
        $this->assertContains('LOG_LEVEL_ERROR',    $logger->messages['phpunit-test-case']);
    }
    
    /**
     * Tests X->setLogger()
     */
    public function testSetLogger() {
        global $argv;
        $argv = array();
        $basePath = X::system()->getPath();
        X::system()->stop(false);
        X::start($basePath);
        
        $this->assertNull(X::system()->getLogger());
        X::system()->log('DEFAULT-LOGGER', 'phpunit-test-case');
        
        $logger = new PHPUnitTestLogger();
        X::system()->setLogger($logger);
        $this->assertInstanceOf('\\X\\Core\\InterfaceLogger', X::system()->getLogger());
    }
    
    /**
     * Tests X->getLogger()
     */
    public function testGetLogger() {
        $logger = new PHPUnitTestLogger();
        X::system()->setLogger($logger);
        $this->assertInstanceOf('\\X\\Core\\InterfaceLogger', X::system()->getLogger());
    }
    
    /**
     * Tests X->setLogLevel()
     * @expectedException X\Core\Exception
     */
    public function testSetLogLevel() {
        $logger = new PHPUnitTestLogger();
        X::system()->setLogger($logger);
        $logger->messages = array();
        X::system()->setLogLevel(InterfaceLogger::LOG_LEVEL_INFO);
        X::system()->log('LOG_LEVEL_VERBOSE',   'phpunit-test-case', InterfaceLogger::LOG_LEVEL_VERBOSE);
        X::system()->log('LOG_LEVEL_DEBUG',     'phpunit-test-case', InterfaceLogger::LOG_LEVEL_DEBUG);
        X::system()->log('LOG_LEVEL_INFO',      'phpunit-test-case', InterfaceLogger::LOG_LEVEL_INFO);
        X::system()->log('LOG_LEVEL_NOTICE',    'phpunit-test-case', InterfaceLogger::LOG_LEVEL_NOTICE);
        X::system()->log('LOG_LEVEL_WARNING',   'phpunit-test-case', InterfaceLogger::LOG_LEVEL_WARNING);
        X::system()->log('LOG_LEVEL_ERROR',     'phpunit-test-case', InterfaceLogger::LOG_LEVEL_ERROR);
        $this->assertNotContains('LOG_LEVEL_VERBOSE',  $logger->messages['phpunit-test-case']);
        $this->assertNotContains('LOG_LEVEL_DEBUG',    $logger->messages['phpunit-test-case']);
        $this->assertContains('LOG_LEVEL_INFO',     $logger->messages['phpunit-test-case']);
        $this->assertContains('LOG_LEVEL_NOTICE',   $logger->messages['phpunit-test-case']);
        $this->assertContains('LOG_LEVEL_WARNING',  $logger->messages['phpunit-test-case']);
        $this->assertContains('LOG_LEVEL_ERROR',    $logger->messages['phpunit-test-case']);
        
        X::system()->setLogLevel('non-interger-level');
    }
    
    /**
     * Tests X->isCLI()
     */
    public function testIsCLI() {
        $this->assertTrue(X::system()->isCLI());
    }
}

class PHPUnitTestLogger implements InterfaceLogger {
    public $messages = array();
    
    public function log($message, $category) {
        $this->messages[$category][] = $message;
    }
}

