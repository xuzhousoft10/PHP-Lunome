<?php
namespace X\Core\Test;

/**
 * 
 */
use X\Core\X;
use X\Core\Util\TestCase;

/**
 * 
 */
class XTest extends TestCase {
    /**
     * 
     */
    public function test_getIsDebugMode() {
        X::system()->getConfiguration()->set('debug', true);
        $this->assertTrue(X::system()->getIsDebugMode());
        
        X::system()->getConfiguration()->set('debug', 1);
        $this->assertFalse(X::system()->getIsDebugMode());
        
        X::system()->getConfiguration()->remove('debug');
        $this->assertFalse(X::system()->getIsDebugMode());
    }
    
    /**
     * 
     */
    public function test_getVersion() {
        $this->assertSame(array(1,0,0), X::system()->getVersion());
    }
}