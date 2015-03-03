<?php
namespace X\Service\XDatabase\Test\SQL\Action;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Action\Rename;
use X\Service\XDatabase\Core\Util\Exception;

/**
 * 
 */
class RenameTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_insert() {
        $rename = new Rename();
        $sql = $rename->newName('table1')->name('table2')->toString();
        $this->assertSame('RENAME TABLE `table2` TO `table1`', $sql);
        
        $rename = new Rename();
        try {
            $rename->newName('table1')->toString();
            $this->fail('An exception should be throwed if no old name when generate rename query.');
        } catch ( Exception $e ){}
        
        $rename = new Rename();
        try {
            $rename->name('table1')->toString();
            $this->fail('An exception should be throwed if no new name when generate rename query.');
        } catch ( Exception $e ){}
        
        $rename = new Rename();
        try {
            $rename->newName('table1')->name('table1')->toString();
            $this->fail('An exception should be throwed if new name is same as old one when generate rename query.');
        } catch ( Exception $e ){}
    }
}