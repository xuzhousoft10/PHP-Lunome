<?php
namespace X\Core\Test\Util;

/**
 * 
 */
use X\Core\Util\TestCase;
use X\Core\Util\Exception;
use X\Core\Util\XUtil;

/**
 * 
 */
class XUtilTest extends TestCase {
    /**
     * 
     */
    public function test_deleteFile() {
        try {
            XUtil::deleteFile('non-exists-path');
            $this->fail('An exception should be throwed if try to delete a non-exists path.');
        } catch ( Exception $e ){}
    }
    
    /**
     * 
     */
    public function test_storeArrayToPHPFile() {
        try {
            XUtil::storeArrayToPHPFile('/non-exists-path', array());
            $this->fail('An exception should be throwed if try to store an array to a non-exists path.');
        } catch ( Exception $e ){}
    }
}