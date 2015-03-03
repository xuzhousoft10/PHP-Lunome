<?php
namespace X\Service\XDatabase\Test;
/**
 * 
 */
use X\Core\X;
use X\Core\Util\TestCase\ServiceTestCase;
use X\Core\Util\Manager;

/**
 * 
 */
class ServiceTest extends ServiceTestCase {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\TestCase\ServiceTestCase::getServiceClass()
     */
    protected function getServiceClass() {
        return 'X\\Service\\XDatabase\\Service';
    }
    
    /**
     * 
     */
    public function test_service() {
        $this->assertSame(Manager::STATUS_RUNNING, $this->manager->getDatabaseManager()->getStatus());
    }
}