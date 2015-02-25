<?php
namespace X\Core\Test\Shortcut;

/**
 * 
 */
use X\Core\X;
use X\Core\Util\TestCase;
use X\Core\Util\Exception;

/**
 * 
 */
class ShortcutTest extends TestCase {
    /**
     * 
     */
    public function test_shortcut() {
        $manager = X::system()->getShortcutManager();
        $manager->register('testshortcut', function( $arg1, $arg2 ) {
            return array($arg1, $arg2);
        });
        $this->assertTrue($manager->has('testshortcut'));
        $this->assertSame(array(1,2), X::system()->testshortcut(1,2));
        
        try {
            $manager->register('testshortcut', array($this, 'test_shortcut'));
            $this->fail('An exception should be throwed if try to register a existsed shortcut.');
        } catch ( Exception $e ) {}
        
        try {
            $manager->register('testshortcut-another', 'uncallable');
            $this->fail('An exception should be throwed if try to register a shortcut with an uncallable handler.');
        } catch ( Exception $e ) {}
        
        try {
            X::system()->non_exists_shortcut();
            $this->fail('An exception should be throwed if try to call a non exists shortcut method.');
        } catch ( Exception $e ){}
        
        try {
            $manager->unregister('non-exists-shortcut');
            $this->fail('An exception should be throwed if try to unregister a non exists shortcut method.');
        } catch ( Exception $e ) {}
        
        $manager->unregister('testshortcut');
        $this->assertFalse($manager->has('testshortcut'));
        
        X::system()->stop(false);
        X::start();
    }
}