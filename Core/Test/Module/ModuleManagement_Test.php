<?php
/**
 *
 */
namespace X\Core\Test\Module;

/**
 * 
 */
use X\Core\X;
use X\Core\Util\XUtil;

/**
 * ModuleManagement test case.
 */
class ModuleManagement_Test extends \PHPUnit_Framework_TestCase {
    /**
     * @var \X\Core\Module\ModuleManagement
     */
    private $ModuleManagement;
    
    /**
     * @var array
     */
    private $oldConfig = array();
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->ModuleManagement = X::system()->getModuleManager();
        $this->oldConfig = $this->ModuleManagement->getConfiguration()->getAll();
        $this->ModuleManagement->stop();
        $this->ModuleManagement->getConfiguration()->setAll(array());
        $this->ModuleManagement->create('PHPUnitTestModule');
        $this->ModuleManagement->enable('PHPUnitTestModule');
        $this->ModuleManagement->start();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        $this->ModuleManagement->stop();
        $this->ModuleManagement->delete('PHPUnitTestModule');
        $this->ModuleManagement->getConfiguration()->setAll($this->oldConfig);
        $this->ModuleManagement->getConfiguration()->save();
        parent::tearDown ();
    }
    
    /**
     * Tests ModuleManagement->start()
     */
    public function testStart() {
        $modules = $this->ModuleManagement->getList();
        $this->assertContains('PHPUnitTestModule', $modules);
    }
    
    /**
     * Tests ModuleManagement->getConfiguration()
     */
    public function testGetConfiguration() {
        $this->assertInstanceOf('\\X\\Core\\Util\\Configuration', $this->ModuleManagement->getConfiguration());
    }
    
    /**
     * Tests ModuleManagement->run()
     */
    public function testRun() {
        /* 通过指定默认模块进行运行 */
        $this->ModuleManagement->stop();
        $this->ModuleManagement->getConfiguration()->merge('PHPUnitTestModule', array('default'=>true));
        $this->ModuleManagement->start();
        ob_start();
        $this->ModuleManagement->run();
        $content = ob_get_contents();
        ob_end_clean();
        $this->assertSame('The module "PHPUnitTestModule" has been created.', $content);
        
        /* 通过设置框架参数进行运行 */
        $this->ModuleManagement->stop();
        $this->ModuleManagement->getConfiguration()->setAll(array());
        $this->ModuleManagement->start();
        /* 当指定不存在的模块时， 会抛出异常， 指明模块不存在 */
        X::system()->setParameter('module', 'non-exists');
        try {
            $this->ModuleManagement->run();
            $this->assertTrue(false);
        } catch ( \X\Core\Module\Exception $e ) {
            $this->assertTrue(true);
        }
        
        /* 尝试启动一个没有启用的模块。 */
        $this->ModuleManagement->stop();
        $this->ModuleManagement->getConfiguration()->setAll(array('PHPUnitTestModule'=>array('enable'=>false)));
        $this->ModuleManagement->start();
        /* 当指定不存在的模块时， 会抛出异常， 指明模块不存在 */
        X::system()->setParameter('module', 'PHPUnitTestModule');
        try {
            $this->ModuleManagement->run();
            $this->assertTrue(false);
        } catch ( \X\Core\Module\Exception $e ) {
            $this->assertTrue(true);
        }
        
        
        
        /* 如果不知道运行那一个模块， 则抛出异常。 */
        $this->ModuleManagement->stop();
        $this->ModuleManagement->getConfiguration()->setAll(array());
        $this->ModuleManagement->start();
        try {
            $this->ModuleManagement->run();
            $this->assertTrue(false);
        } catch ( \X\Core\Module\Exception $e ) {
            $this->assertTrue(true);
        }
    }
    
    /**
     * Tests ModuleManagement->getList()
     */
    public function testGetList() {
        $modules = $this->ModuleManagement->getList();
        $this->assertContains('PHPUnitTestModule', $modules);
    }
    
    /**
     * Tests ModuleManagement->has()
     */
    public function testHas() {
        $this->assertTrue($this->ModuleManagement->has('PHPUnitTestModule'));
        $this->assertFalse($this->ModuleManagement->has('non-exists'));
    }
    
    /**
     * Tests ModuleManagement->get()
     */
    public function testGet() {
        $this->assertInstanceOf('\\X\\Module\\PHPUnitTestModule\\Module', $this->ModuleManagement->get('PHPUnitTestModule'));
        
        try {
            $this->ModuleManagement->get('non-exists');
            $this->assertTrue(false);
        } catch ( \X\Core\Module\Exception $e ) {
            $this->assertTrue(true);
        }
    }
    
    /**
     * Tests ModuleManagement->isEnable()
     */
    public function testIsEnable() {
        $this->assertFalse($this->ModuleManagement->isEnable('non-exists'));
        $this->assertTrue($this->ModuleManagement->isEnable('PHPUnitTestModule'));
    }
    
    /**
     * Tests ModuleManagement->enable()
     */
    public function testEnable() {
        try {
            $this->ModuleManagement->enable('non-exists');
            $this->assertTrue(false);
        } catch ( \X\Core\Module\Exception $e ) {
            $this->assertTrue(true);
        }
        
        /* 如果模块的类不存在， 则无法启用 */
        $this->ModuleManagement->getConfiguration()->set('ClassNonExistsModule', array('enable'=>false));
        try {
            $this->ModuleManagement->enable('ClassNonExistsModule');
            $this->assertTrue(false);
        } catch ( \X\Core\Module\Exception $e ) {
            $this->assertTrue(true);
        }
        
        /* 如果模块的类不是一个合法的模块类， 则无法启用 */
        mkdir(X::system()->getPath('Module/ClassNotAvailable'));
        $content = "<?php\nnamespace X\\Module\\ClassNotAvailable;\nclass Module {}";
        file_put_contents(X::system()->getPath('Module/ClassNotAvailable/Module.php'), $content);
        $this->ModuleManagement->getConfiguration()->set('ClassNotAvailable', array('enable'=>false));
        try {
            $this->ModuleManagement->enable('ClassNotAvailable');
            $this->assertTrue(false);
        } catch ( \X\Core\Module\Exception $e ) {
            $this->assertTrue(true);
        }
        XUtil::deleteFile(X::system()->getPath('Module/ClassNotAvailable'));
    }
    
    /**
     * Tests ModuleManagement->disable()
     * @expectedException X\Core\Module\Exception
     */
    public function testDisable() {
        $this->ModuleManagement->disable('PHPUnitTestModule');
        $this->assertFalse($this->ModuleManagement->has('PHPUnitTestModule'));
        
        $this->ModuleManagement->disable('non-exists');
    }
    
    /**
     * Tests ModuleManagement->create()
     * @expectedException X\Core\Module\Exception
     */
    public function testCreate() {
        $this->ModuleManagement->create('PHPUnitTestCreateModule');
        $this->assertTrue($this->ModuleManagement->has('PHPUnitTestCreateModule'));
        $this->ModuleManagement->delete('PHPUnitTestCreateModule');
        
        $this->ModuleManagement->create('PHPUnitTestModule');
    }
    
    /**
     * Tests ModuleManagement->setDefault()
     * @expectedException X\Core\Module\Exception
     */
    public function testSetDefault() {
        $this->ModuleManagement->setDefault('PHPUnitTestModule');
        $this->assertTrue($this->ModuleManagement->isDefault('PHPUnitTestModule'));
        
        $this->ModuleManagement->setDefault(null);
        $this->assertFalse($this->ModuleManagement->isDefault('PHPUnitTestModule'));
        
        $this->ModuleManagement->setDefault('non-exists');
    }
    
    /**
     * Tests ModuleManagement->isDefault()
     * @expectedException X\Core\Module\Exception
     */
    public function testIsDefault() {
        $this->ModuleManagement->setDefault('PHPUnitTestModule');
        $this->assertTrue($this->ModuleManagement->isDefault('PHPUnitTestModule'));
        
        $this->ModuleManagement->setDefault(null);
        $this->assertFalse($this->ModuleManagement->isDefault('PHPUnitTestModule'));
        
        $this->ModuleManagement->setDefault('non-exists');
    }
    
    /**
     * Tests ModuleManagement->delete()
     */
    public function testDelete() {
        $this->ModuleManagement->create('PHPUnitTestCreateModule');
        $this->assertTrue($this->ModuleManagement->has('PHPUnitTestCreateModule'));
        $this->ModuleManagement->delete('PHPUnitTestCreateModule');
        $this->assertFalse($this->ModuleManagement->has('PHPUnitTestCreateModule'));
    }
    
    /**
     * Tests ModuleManagement->migrateCreate()
     * @expectedException X\Core\Module\Exception
     */
    public function testMigrateCreate() {
        $this->ModuleManagement->migrateCreate('PHPUnitTestModule', 'First_Migration');
        $this->ModuleManagement->migrateUp('PHPUnitTestModule');
        $path = X::system()->getPath('Module/PHPUnitTestModule/Migration/History.php');
        $this->assertTrue(is_file($path));
        
        $this->ModuleManagement->migrateCreate('PHPUnitTestModule', 'Second_Migration');
        $this->ModuleManagement->migrateUp('PHPUnitTestModule');
        $path = X::system()->getPath('Module/PHPUnitTestModule/Migration');
        $this->assertSame(5, count(scandir($path)));
        
        $this->ModuleManagement->migrateCreate('non-exists', 'xxxx');
    }
    
    /**
     * Tests ModuleManagement->migrateUp()
     * @expectedException X\Core\Module\Exception
     */
    public function testMigrateUp() {
        $this->ModuleManagement->migrateCreate('PHPUnitTestModule', 'First_Migration');
        $this->ModuleManagement->migrateUp('PHPUnitTestModule');
        $path = X::system()->getPath('Module/PHPUnitTestModule/Migration/History.php');
        $this->assertTrue(is_file($path));
        
        file_put_contents(X::system()->getPath('Module/PHPUnitTestModule/Migration/.not-exists.php'), '');
        
        $this->ModuleManagement->migrateCreate('PHPUnitTestModule', 'Second_Migration');
        $this->ModuleManagement->migrateUp('PHPUnitTestModule');
        $path = X::system()->getPath('Module/PHPUnitTestModule/Migration/History.php');
        $this->assertSame(2, count(require $path));
        
        $this->ModuleManagement->migrateUp('non-exists');
    }
    
    /**
     * Tests ModuleManagement->migrateDown()
     * @expectedException X\Core\Module\Exception
     */
    public function testMigrateDown() {
        $this->ModuleManagement->migrateCreate('PHPUnitTestModule', 'First_Migration');
        $this->ModuleManagement->migrateCreate('PHPUnitTestModule', 'Second_Migration');
        $this->ModuleManagement->migrateUp('PHPUnitTestModule');
        $path = X::system()->getPath('Module/PHPUnitTestModule/Migration/History.php');
        $this->assertSame(2, count(require $path));
        
        $this->ModuleManagement->migrateDown('PHPUnitTestModule', 100);
        $this->assertSame(0, count(require $path));
        
        $this->ModuleManagement->migrateUp('PHPUnitTestModule');
        $this->ModuleManagement->migrateDown('PHPUnitTestModule', 1);
        $this->assertSame(1, count(require $path));
        
        $this->ModuleManagement->migrateDown('non-exists', 100);
    }
}

