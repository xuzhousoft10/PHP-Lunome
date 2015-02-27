<?php
namespace X\Service\XDatabase\Test\SQL\Condition;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Condition\Group;
/**
 * 
 */
class GroupTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_group() {
        $this->assertSame('(', Group::start()->toString());
        $this->assertSame(')', Group::end()->toString());
    }
}