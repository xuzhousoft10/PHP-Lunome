<?php
namespace X\Core\Test\Util;

/**
 * 
 */
use X\Core\Util\TestCase;
use X\Core\Test\Fixture\Util\ConfigurationArray;

/**
 * @author Michael Luthor <michaelluthor@163.com> 
 * @version 0.0.0
 */
class ConfigurationArrayTest extends TestCase {
    /**
     * @var ConfigurationArray
     */
    private $arrayExt = null;
    
    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp() {
        $this->arrayExt = new ConfigurationArray();
        parent::setUp();
    }
    
    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown() {
        parent::tearDown();
        $this->arrayExt = null;
    }
    
    /**
     * 
     */
    public function test_offsetExists() {
         $this->assertFalse(isset($this->arrayExt['non-exists']));
        
         $this->arrayExt['key1'] = array();
         $this->assertTrue(isset($this->arrayExt['key1']));
        
         $this->arrayExt['key1']['key1-1'] = true;
         $this->assertTrue(isset($this->arrayExt['key1']['key1-1']));
         $this->assertFalse(isset($this->arrayExt['key1']['key1-2']));
    }
    
    /**
     *
     */
    public function test_offsetGet() {
         $this->arrayExt['k1'] = 'value1';
         $this->assertSame('value1', $this->arrayExt['k1']);
        
         $this->arrayExt['k2']=array();
         $this->assertSame(array(), $this->arrayExt['k2']);
         $this->arrayExt['k2']['k2-2'] = 'value2';
         $this->assertSame('value2', $this->arrayExt['k2']['k2-2']);
        
         $this->assertNull($this->arrayExt['non-exists']);
    }
    
    /**
     *
     */
    public function test_offsetSet() {
        $this->arrayExt['k1'] = 'value1';
        $this->assertSame('value1', $this->arrayExt['k1']);
        
        $this->arrayExt['k2']=array();
        $this->assertSame(array(), $this->arrayExt['k2']);
        $this->arrayExt['k2']['k2-2'] = 'value2';
        $this->assertSame('value2', $this->arrayExt['k2']['k2-2']);
        
        $this->arrayExt[] = 'appended-value';
        $this->assertSame('appended-value', $this->arrayExt[0]);
    }
    
    /**
     *
     */
    public function test_offsetUnset() {
        $this->arrayExt[0] = 'value1';
        $this->assertSame('value1', $this->arrayExt[0]);
        unset($this->arrayExt[0]);
        $this->assertNull($this->arrayExt[0]);
    }
    
    /**
     *
     */
    public function test_current() {
        $this->arrayExt[] = 'value1';
        $this->arrayExt[] = 'value2';
        $this->arrayExt[] = 'value3';
        $this->arrayExt[] = 'value4';
        $this->arrayExt[] = 'value5';
        
        $index = 1;
        foreach ( $this->arrayExt as $key => $value ) {
            $this->assertSame('value'.$index, $value);
            $index++;
        }
    }
    
    /**
     *
     */
    public function test_next() {
        $this->test_current();
    }
    
    /**
     *
     */
    public function test_key() {
        $this->test_current();
    }
    
    /**
     *
     */
    public function test_valid() {
        $this->test_current();
    }
    
    /**
     *
     */
    public function test_rewind() {
        $this->test_current();
    }
    
    /**
     *
     */
    public function test___destruct() {
        $this->arrayExt->__destruct();
        $this->assertSame(0, $this->arrayExt->getLength());
    }
    
    /**
     *
     */
    public function test_set() {
        $this->arrayExt->set('append-value', 'value1');
        $this->assertSame('value1', $this->arrayExt['append-value']);
        
        $this->arrayExt->set('update-value', 'value2');
        $this->arrayExt->set('update-value', 'value3');
        $this->assertSame('value3', $this->arrayExt['update-value']);
    }
    
    /**
     *
     */
    public function test_get() {
        $this->assertNull($this->arrayExt->get('non-exists'));
        $this->assertSame('default-value', $this->arrayExt->get('test-detaul', 'default-value'));
        $this->arrayExt['exists-value'] = 'existsed';
        $this->assertSame('existsed', $this->arrayExt->get('exists-value'));
    }
    
    /**
     * 
     */
    public function test_merge() {
        $this->arrayExt['k1'] = 'value1';
        $this->arrayExt->merge(array('k1'=>'value2'));
        $this->assertSame(array('value1', 'value2'), $this->arrayExt['k1']);
    }
    
    /**
     * 
     */
    public function test_has() {
        $this->assertFalse($this->arrayExt->has('non-exists'));
        
        $this->arrayExt['k1'] = 'value1';
        $this->assertTrue($this->arrayExt->has('k1'));
        
        $this->arrayExt['k1'] = null;
        $this->assertTrue($this->arrayExt->has('k1'));
    }
    
    /**
     * 
     */
    public function test_remove() {
        $this->assertFalse($this->arrayExt->has('non-exists'));
        $this->arrayExt->remove('non-exists');
        $this->assertFalse($this->arrayExt->has('non-exists'));
        
        $this->arrayExt['k1'] = 'value1';
        $this->assertTrue($this->arrayExt->has('k1'));
        $this->arrayExt->remove('k1');
        $this->assertFalse($this->arrayExt->has('k1'));
        
        $this->arrayExt['k1'] = null;
        $this->assertTrue($this->arrayExt->has('k1'));
        $this->arrayExt->remove('k1');
        $this->assertFalse($this->arrayExt->has('k1'));
    }
}