<?php
namespace X\Core\Test\Environment;

/**
 * 
 */
use X\Core\X;
use X\Core\Util\TestCase;
use X\Core\Environment\Environment;
use X\Core\Util\Exception;

/**
 * 
 */
class EnvironmentTest extends TestCase {
    /**
     * 
     */
    public function test_Environment() {
        $env = X::system()->getEnvironment();
        $this->assertSame('cli', $env->getName());
        $this->assertSame(array(), $env->getParameters());
        
        /* test env from outside. */
        global $argv;
        $argv[] = '--test-arg1=value1';
        $argv[] = '--test-arg2=value2';
        $env = new Environment();
        $this->assertSame('cli', $env->getName());
        $this->assertSame(array('test-arg1'=>'value1', 'test-arg2'=>'value2'), $env->getParameters());
        
        try {
            $env->non_exists_method();
            $this->fail('An exception should be throwed if try to call a non exists method to from Environment.');
        } catch ( Exception $e ) {}
    }
}