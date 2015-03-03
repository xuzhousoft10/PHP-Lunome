<?php
namespace X\Service\XDatabase\Test\SQL\Action\Func;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Func\Count;
/**
 *
 */
class CountTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_count() {
        $count = new Count();
        $this->assertSame("COUNT(*)", $count->toString());
        
        $count = new Count('table1.col1');
        $this->assertSame("COUNT(`table1`.`col1`)", $count->toString());
    }
}