<?php
namespace X\Service\XView\Test\Core\Util\HtmlView;
/**
 * 
 */
use X\Core\Util\TestCase;
use X\Service\XView\Core\Util\HtmlView\ScriptManager;
use X\Service\XView\Core\Util\Exception;
/**
 * 
 */
class ScriptManagerTest extends TestCase {
    /**
     * 
     */
    public function test_ScriptManager() {
        $manager = new ScriptManager();
        
        $manager->addString('test1', 'alert("value1");');
        $this->assertTrue($manager->has('test1'));
        $manager->addFile('test2', '/assets/js/js01.js');
        $this->assertTrue($manager->has('test1'));
        $this->assertSame(array('test1', 'test2'), $manager->getList());
        $script = $manager->get('test1');
        $this->assertSame('alert("value1");', $script['content']);
        $manager->addString('test3', 'alert("value3");');
        $this->assertTrue($manager->has('test3'));
        $manager->remove('test3');
        $this->assertFalse($manager->has('test3'));
        
        $manager->addString('test5', '');
        $this->assertFalse($manager->has('test5'));
        
        $manager->addFile('test4', '');
        $this->assertFalse($manager->has('test4'));
        
        $script = array();
        $script[] = '<script type="text/javascript">';
        $script[] = 'alert("value1");';
        $script[] = '</script>';
        $script[] = '<script type="text/javascript" src="/assets/js/js01.js" charset="UTF-8"></script>';
        $script = implode("\n", $script);
        $this->assertSame($script, $manager->toString());
        
        try {
            $manager->get('non-exists');
            $this->fail('An exception should be throwed if you try to get non exists script.');
        } catch ( Exception $e ) {}
    }
}