<?php
namespace X\Service\XDatabase\Test\SQL\Action;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Action\Describe;
use X\Service\XDatabase\Core\Util\Exception;
/**
 * 
 */
class DescribeTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_describe() {
        $describe = new Describe();
        $sql = $describe->name('table1')->toString();
        $this->assertSame('DESCRIBE `table1`', $sql);
        
        try {
            $describe = new Describe();
            $describe->toString();
            $this->fail('An exception should be throwed if try to generate a describe without a table name.');
        } catch ( Exception $e ){}
    }
}