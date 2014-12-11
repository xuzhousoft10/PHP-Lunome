<?php
/**
 * 
 */
namespace X\Core\Test\Util;

/**
 * 
 */
use X\Core\Util\Configuration;
use X\Core\Util\XUtil;

/**
 * Configuration test case.
 */
class Configuration_Test extends \PHPUnit_Framework_TestCase {
    /**
     *
     * @var Configuration
     */
    private $Configuration;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $path = dirname(__FILE__).DIRECTORY_SEPARATOR.'tmp.php';
        $this->Configuration = new Configuration($path);
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        $path = dirname(__FILE__).DIRECTORY_SEPARATOR.'tmp.php';
        if ( is_file($path) ) {
            unlink();
        }
        $this->Configuration = null;
        parent::tearDown ();
    }
    
    /**
     * Tests Configuration->__construct()
     */
    public function test__construct() {
        $this->assertSame(array(), $this->Configuration->getAll());
        
        $path = dirname(__FILE__).DIRECTORY_SEPARATOR.'tmp2.php';
        $data = array('value'=>'value');
        XUtil::storeArrayToPHPFile($path, $data);
        $configuration = new Configuration($path);
        $this->assertSame($data, $configuration->getAll());
        unlink($path);
    }
    
    /**
     * Tests Configuration->set()
     */
    public function testSet() {
        $this->Configuration->set('value1', 'value1');
        $this->assertContains('value1', $this->Configuration->getAll());
        $this->assertArrayHasKey('value1', $this->Configuration->getAll());
        $this->assertSame('value1', $this->Configuration->get('value1'));
        
        $this->Configuration->set('value1', 'new value');
        $this->assertSame('new value', $this->Configuration->get('value1'));
    }
    
    /**
     * Tests Configuration->get()
     */
    public function testGet() {
        $this->Configuration->set('values.value1', 'value1');
        $this->assertSame('value1', $this->Configuration->get('values.value1'));
        $this->assertSame(null, $this->Configuration->get('values.non_exists'));
    }
    
    /**
     * Tests Configuration->getAll()
     */
    public function testGetAll() {
        $this->assertSame(array(), $this->Configuration->getAll());
        
        $path = dirname(__FILE__).DIRECTORY_SEPARATOR.'tmp2.php';
        $data = array('value'=>'value');
        XUtil::storeArrayToPHPFile($path, $data);
        $configuration = new Configuration($path);
        $this->assertSame($data, $configuration->getAll());
        unlink($path);
    }
    
    /**
     * Tests Configuration->setAll()
     */
    public function testSetAll() {
        $path = dirname(__FILE__).DIRECTORY_SEPARATOR.'tmp2.php';
        $data = array('value'=>'value');
        XUtil::storeArrayToPHPFile($path, $data);
        $configuration = new Configuration($path);
        $this->assertSame($data, $configuration->getAll());
        
        $newData = array('new-value'=>'new-value');
        $configuration->setAll($newData);
        $this->assertSame($newData, $configuration->getAll());
        unlink($path);
    }
    
    /**
     * Tests Configuration->save()
     */
    public function testSave() {
        $path = dirname(__FILE__).DIRECTORY_SEPARATOR.'tmp2.php';
        $data = array('value'=>'value');
        $configuration = new Configuration($path);
        $configuration->save();
        $this->assertFalse(is_file($path));
        $configuration->setAll($data);
        $configuration->save();
        $this->assertTrue(is_file($path));
        
        $configuration = new Configuration($path);
        $this->assertSame($data, $configuration->getAll());
        unlink($path);
    }
    
    /**
     * Tests current(), next(), key(), valid(), rewind()
     */
    public function test_Iterator() {
        $data = array('value'=>'value');
        $this->Configuration->setAll($data);
        foreach ( $this->Configuration as $item => $value ) {
            $this->assertSame($data[$item], $value);
        }
    }
    
    /**
     * Tests Configuration->offsetExists()
     */
    public function testOffsetExists() {
        $data = array('value'=>'value');
        $this->Configuration->setAll($data);
        $this->assertTrue(isset($this->Configuration['value']));
        $this->assertFalse(isset($this->Configuration['non-exists']));
    }
    
    /**
     * Tests Configuration->offsetGet()
     */
    public function testOffsetGet() {
        $data = array('value'=>'value');
        $this->Configuration->setAll($data);
        $this->assertSame('value', $this->Configuration['value']);
        $this->assertNull(@$this->Configuration['non-exists']);
    }
    
    /**
     * Tests Configuration->offsetSet()
     */
    public function testOffsetSet() {
        $this->Configuration['value'] = 'value';
        $this->assertSame('value', $this->Configuration['value']);
        $this->Configuration['non-exists'] = 'new value';
        $this->assertSame('new value', $this->Configuration['non-exists']);
        $this->Configuration[] = 'no indexed value';
        $this->assertSame('no indexed value', $this->Configuration[0]);
    }
    
    /**
     * Tests Configuration->offsetUnset()
     */
    public function testOffsetUnset() {
        $data = array('value'=>'value');
        $this->Configuration->setAll($data);
        $this->assertSame('value', $this->Configuration['value']);
        unset($this->Configuration['value']);
        $this->assertNull(@$this->Configuration['value']);
        unset($this->Configuration['non-exists']);
    }
}

