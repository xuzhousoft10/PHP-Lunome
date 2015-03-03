<?php
namespace X\Service\XDatabase\Test\SQL\Condition;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Condition\Connector;
/**
 * 
 */
class ConnectorTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_group() {
        $this->assertSame('AND', Connector::cAnd()->toString());
        $this->assertSame('OR', Connector::cOr()->toString());
    }
}