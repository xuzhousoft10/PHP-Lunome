<?php
namespace X\Service\XDatabase\Test\SQL\Action;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Action\Delete;
use X\Service\XDatabase\Core\Util\Exception;
/**
 *
 */
class DeleteTest extends ServiceTestCase {
    /**
     * @var Delete
     */
    private $delete = null;
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Test\Util\ServiceTestCase::setUp()
     */
    protected function setUp() {
        parent::setUp();
        $this->delete = new Delete();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\TestCase\ServiceTestCase::tearDown()
     */
    protected function tearDown() {
        $this->delete = null;
        parent::tearDown();
    }
    
    /**
     * 
     */
    public function test_deleteFromSingleTable() {
        $sql = $this->delete->from('table1')->toString();
        $this->assertSame('DELETE FROM `table1`', $sql);
    }
    
    /**
     * 
     */
    public function test_deleteFromNoTable() {
        try {
            $this->delete->toString();
            $this->fail('An exception should be throwed if no table been setted.');
        } catch ( Exception $e ){}
    }
}