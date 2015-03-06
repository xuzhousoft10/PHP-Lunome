<?php
namespace X\Service\XView\Test\Core\Util\HtmlView;
/**
 * 
 */
use X\Core\Util\TestCase;
use X\Service\XView\Core\Handler\Html;
use X\Service\XView\Core\Util\Exception;
/**
 * 
 */
class ParticleViewManagerTest extends TestCase {
    /**
     * 
     */
    public function test_ParticleViewManager(){
        $html = new Html();
        $manager = $html->getParticleViewManager();
        
        $this->assertFalse($manager->has('test'));
        $manager->load('test', 'STRING');
        $this->assertTrue($manager->has('test'));
        $this->assertContains('test', $manager->getList());
        $particle = $manager->get('test');
        $this->assertSame('STRING', $particle->toString());
        
        try {
            $manager->get('non-exists');
            $this->fail('An exception should be throwed if try to get non exists particle view.');
        } catch ( Exception $e ){}
        
        $manager->cleanUp();
        $this->assertSame('STRING', $manager->toString());
        $manager->remove('test');
        $this->assertFalse($manager->has('test'));
        $this->assertSame('', $manager->toString());
    }
}