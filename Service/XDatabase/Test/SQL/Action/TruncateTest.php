<?php
namespace X\Service\XDatabase\Test\SQL\Action;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Action\Truncate;
use X\Service\XDatabase\Core\Util\Exception;

/**
 * 
 */
class TruncateTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_truncate() {
        $truncate = new Truncate();
        $sql = $truncate->name('table1')->toString();
        $this->assertSame('TRUNCATE TABLE `table1`', $sql);
        
        $truncate = new Truncate();
        try {
            $truncate->toString();
            $this->fail('An exception should be throwed if name is not setted.');
        } catch ( Exception $e ){}
    }
}