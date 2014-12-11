<?php
/**
 * 
 */
namespace X\Core\Test;

/**
 * 
 */
use X\Core\Basic;

/**
 * 
 */
class Basic_Test extends \PHPUnit_Framework_TestCase {
    /**
     * @var PHPUnitTestBasic
     */
    private $basic = null;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->basic = new PHPUnitTestBasic();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        $this->basic = null;
        parent::tearDown ();
    }
    
    /**
     * 
     */
    public function test__isset() {
        $this->assertTrue(isset($this->basic->value1));
        $this->assertFalse(isset($this->basic->value2));
        $this->assertTrue(isset($this->basic->value3));
        $this->assertFalse(isset($this->basic->value4));
        $this->assertFalse(isset($this->basic->non_exists));
    }
    
    /**
     * @expectedException X\Core\Exception
     */
    public function test__get() {
        $this->assertSame('value1', $this->basic->value1);
        $this->assertSame('value3', $this->basic->value3);
        $this->assertNull($this->basic->get('value4'));
        
        $basic = new PHPUnitTestBasicNoGetSetMethod();
        $basic->non_exists;
    }
    
    /**
     * @expectedException X\Core\Exception
     */
    public function test__set() {
        $this->basic->value5 = 'value5';
        $this->assertSame('value5', $this->basic->valueContainer);
        
        $this->basic->value6 = 'value6';
        $this->assertSame('value6', $this->basic->valueContainer);
        
        $basic = new PHPUnitTestBasicNoGetSetMethod();
        $basic->non_exists = 'error';
    }
    
    /**
     * 
     */
    public function test__toString() {
        $this->assertSame('X\\Core\\Test\\PHPUnitTestBasic', $this->basic.'');
        $basic = new PHPUnitTestBasicNoGetSetMethod();
        $this->assertSame('value7', $basic.'');
    }
    
    /**
     * 
     */
    public function testToString() {
        $this->assertSame('X\\Core\\Test\\PHPUnitTestBasic', $this->basic->toString());
        $basic = new PHPUnitTestBasicNoGetSetMethod();
        $this->assertSame('value7', $basic->toString());
    }
}

class PHPUnitTestBasic extends Basic {
    public function getValue1() {
        return 'value1';
    }
    
    public function getValue2() {
        return null;
    }
    
    public function get( $name ) {
        if ( 'value3' === $name ) {
            return 'value3';
        } else if ( 'value4' === $name ) {
            return null;
        }
    }
    
    public $valueContainer = null;
    public function setValue5($value) {
        $this->valueContainer = $value;
    }
    public function set( $name, $value ) {
        if ( 'value6' === $name ) {
            $this->valueContainer = $value;
        }
    }
}

class PHPUnitTestBasicNoGetSetMethod extends Basic {
    public function getValue1() {
        return 'value1';
    }

    public function getValue2() {
        return null;
    }
    
    public function toString() {
        return 'value7';
    }
}




