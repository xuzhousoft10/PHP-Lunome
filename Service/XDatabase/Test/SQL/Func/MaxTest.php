<?php
namespace X\Service\XDatabase\Test\SQL\Action\Func;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Func\Max;
/**
 *
 */
class MaxTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_max() {
        $max = new Max('table1.col1');
        $this->assertSame("MAX(`table1`.`col1`)", $max->toString());
    }
}