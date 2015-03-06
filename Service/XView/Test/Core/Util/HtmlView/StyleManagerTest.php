<?php
namespace X\Service\XView\Test\Core\Util\HtmlView;
/**
 * 
 */
use X\Core\Util\TestCase;
use X\Service\XView\Core\Util\HtmlView\StyleManager;
use X\Service\XView\Core\Util\Exception;
/**
 * 
 */
class StyleManagerTest extends TestCase {
    /**
     * 
     */
    public function test_StyleManager() {
        $manager = new StyleManager();
        $manager->add('body', array('background-color'=>'#000000'), 'screen');
        $manager->add('body', array('width'=>'100%'), 'screen');
        $manager->set('.container', 'width', 'auto');
        $this->assertSame('auto', $manager->get('.container', 'width'));
        $manager->add('.header', array('height'=>'50px', 'width'=>'100%'));
        $manager->add('.footer', array('height'=>'50px', 'width'=>'100%'));
        $manager->remove('.footer');
        $manager->removeAttribute('.header', 'width');
        
        $style = array();
        $style[] = '<style type="text/css">';
        $style[] = '@media screen { body {background-color:#000000;width:100%;} }';
        $style[] = '.container {width:auto;}';
        $style[] = '.header {height:50px;}';
        $style[] = '</style>';
        $style = implode("\n", $style);
        $this->assertSame($style, $manager->toString());
        
        $manager->removeAttribute('non-exists', 'existsed');
        $this->assertSame($style, $manager->toString());
        
        $manager->removeAttribute('body', 'non-exists', 'screen');
        $this->assertSame($style, $manager->toString());
        
        $manager->removeAttribute('.container', 'width');
        $style = array();
        $style[] = '<style type="text/css">';
        $style[] = '@media screen { body {background-color:#000000;width:100%;} }';
        $style[] = '.header {height:50px;}';
        $style[] = '</style>';
        $style = implode("\n", $style);
        $this->assertSame($style, $manager->toString());
        
        $styles = array (
            'body@screen' => array (
                'style' => array (
                    'background-color' => '#000000',
                    'width' => '100%',),
                'item' => 'body',
                'media' => 'screen',),
            '.header' => array (
                'style' => array ('height' => '50px',),
                'item' => '.header',
                'media' => NULL,),);
        $this->assertSame($styles, $manager->getStyles());
        
        $this->assertNull($manager->get('non-exists', 'existsed'));
        $this->assertNull($manager->get('body', 'existsed', 'screen'));
        
        try {
            $manager->add('', array());
            $this->fail('An exception should be throwed if argument error to add method.');
        } catch ( Exception $e ) {}
        
        try {
            $manager->set('', '', '');
            $this->fail('An exception should be throwed if argument error to set method.');
        } catch ( Exception $e ) {}
    }
}