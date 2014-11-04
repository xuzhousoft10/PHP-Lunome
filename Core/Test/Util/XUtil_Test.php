<?php
/**
 *
 */
namespace X\Core\Test\Util;

use X\Core\Util\XUtil;
/**
 * XUtil test case.
 */
class XUtil_Test extends \PHPUnit_Framework_TestCase {
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }
    
    /**
     * Tests XUtil::storeArrayToPHPFile()
     */
    public function testStoreArrayToPHPFile() {
        $path = dirname(__FILE__).DIRECTORY_SEPARATOR.'tmp.php';
        $data = array('value'=>'value', 'valueArray'=>array('a1'=>'a1'));
        XUtil::storeArrayToPHPFile($path, $data);
        $sotredData = require $path;
        $this->assertSame($data, $sotredData);
        unlink($path);
    }
    
    /**
     * Tests XUtil::deleteFile()
     * @expectedException X\Core\Exception
     */
    public function testDeleteFile() {
        $foler = dirname(__FILE__).DIRECTORY_SEPARATOR.'tmp';
        mkdir($foler);
        mkdir($foler.DIRECTORY_SEPARATOR.'subfolder');
        file_put_contents($foler.DIRECTORY_SEPARATOR.'tmp.txt', 'sss');
        XUtil::deleteFile($foler);
        $this->assertFalse(is_dir($foler));
        
        XUtil::deleteFile('non-exists');
    }
}

