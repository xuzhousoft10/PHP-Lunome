<?php
namespace X\Service\XDatabase\Test\SQL\Action;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Action\DropTable;
use X\Service\XDatabase\Core\Util\Exception;
/**
 * 
 */
class DropTableTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_describe() {
        $dropTable = new DropTable();
        $sql = $dropTable->name('table1')->toString();
        $this->assertSame('DROP TABLE `table1`', $sql);
        
        try {
            $dropTable = new DropTable();
            $dropTable->toString();
            $this->fail('An exception should be throwed if try to generate a drop table sql without a table name.');
        } catch ( Exception $e ){}
    }
}