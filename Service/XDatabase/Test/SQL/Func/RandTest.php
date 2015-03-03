<?php
namespace X\Service\XDatabase\Test\SQL\Action\Func;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Func\Rand;
/**
 *
 */
class RandTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_rand() {
        $rand = new Rand();
        $this->assertSame('RAND()', $rand->toString());
    }
}