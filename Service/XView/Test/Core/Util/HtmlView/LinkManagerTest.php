<?php
namespace X\Service\XView\Test\Core\Util\HtmlView;
/**
 * 
 */
use X\Service\XView\Core\Util\HtmlView\LinkManager;
use X\Core\Util\TestCase;
use X\Service\XView\Core\Util\Exception;
/**
 * 
 */
class LinkManagerTest extends TestCase {
    /**
     * 
     */
    public function test_LinkManagerTest() {
        $manager = new LinkManager();
        $content = $manager->toString();
        $this->assertSame(null, $content);
        
        $manager->addCSS('css-1', '/assets/css/css-1.css');
        $this->assertTrue($manager->has('css-1'));
        $manager->addCSS('css-1', '/assets/css/css-1-last.css');
        $manager->addCSS('css-2', '/assets/css/css-2.css');
        $this->assertTrue($manager->has('css-2'));
        $manager->remove('css-2');
        $this->assertFalse($manager->has('css-2'));
        $link = $manager->get('css-1');
        $this->assertSame('/assets/css/css-1-last.css', $link['href']);
        try {
            $manager->get('css-2');
            $this->fail('An exception should be throwed if you try to get non-exists link.');
        } catch ( Exception $e ) {}
        $this->assertContains('css-1', $manager->getList());
        
        $manager->setFavicon('/assets/images/favicon.ico');
        $content = array(
            '<link rel=stylesheet href=/assets/css/css-1-last.css type=text/css />',
            '<link rel=icon href=/assets/images/favicon.ico type=image/x-icon />',
        );
        $content = implode("\n", $content);
        $this->assertSame($content, $manager->toString());
    }
}