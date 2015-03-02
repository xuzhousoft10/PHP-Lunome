<?php
namespace X\Service\XDatabase\Test\ActiveRecord;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
/**
 * 
 */
class CriteriaTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_criteria() {
        $criteria = new Criteria();
        $criteria->addOrder('id', 'ASC');
        $this->assertSame(array(array('expression'=>'id', 'order'=>'ASC')), $criteria->getOrders());
    }
}