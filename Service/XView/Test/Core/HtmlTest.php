<?php
/**
 * Namespace defination
 */
namespace X\Service\XView\Core\Handler;
/**
 * 
 */
use X\Core\X;
use X\Core\Util\TestCase;
/**
 * The view handler for html page.
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version Version 0.0.0
 */
class HtmlTest extends TestCase {
    /**
     * 
     */
    public function test_Layout() {
        $html = new Html();
        $html->title = 'Test HTML View';
        $html->getDataManager()->set('val1', 'value1');
        
        $content = array();
        $content[] = '<!DOCTYPE html>';
        $content[] = '<html xmlns=\"http://www.w3.org/1999/xhtml\">';
        $content[] = '<head>';
        $content[] = '<title>Test HTML View</title>';
        $content[] = '</head>';
        $content[] = ' ';
        $content[] = '</html>';
        $content = implode("\n", $content);
        ob_start();
        ob_implicit_flush(false);
        $html->display();
        $viewContent = ob_get_clean();
        $this->assertSame($content, $viewContent);
        $html->cleanUp();
        
        $html->setLayout(X::system()->getPath('Service/XView/Test/Fixture/View/Html/Layout.php'));
        $content = array();
        $content[] = '<!DOCTYPE html>';
        $content[] = '<html xmlns=\"http://www.w3.org/1999/xhtml\">';
        $content[] = '<head>';
        $content[] = '<title>Test HTML View</title>';
        $content[] = '</head>';
        $content[] = ' ';
        $content[] = 'val1:value1';
        $content[] = '';
        $content[] = '</html>';
        $content = implode("\n", $content);
        ob_start();
        ob_implicit_flush(false);
        $html->display();
        $viewContent = ob_get_clean();
        $this->assertSame($content, $viewContent);
        $html->cleanUp();
        
        $html->setLayout(array($this, 'layoutHandler'));
        $content = array();
        $content[] = '<!DOCTYPE html>';
        $content[] = '<html xmlns=\"http://www.w3.org/1999/xhtml\">';
        $content[] = '<head>';
        $content[] = '<title>Test HTML View</title>';
        $content[] = '</head>';
        $content[] = ' ';
        $content[] = 'val1:value1';
        $content[] = '</html>';
        $content = implode("\n", $content);
        ob_start();
        ob_implicit_flush(false);
        $html->display();
        $viewContent = ob_get_clean();
        $this->assertSame($content, $viewContent);
        $html->cleanUp();
    }
    
    /**
     * @param Html $html
     * @return string
     */
    public function layoutHandler( Html $html) {
        return 'val1:'.$html->getDataManager()->get('val1');
    }
}