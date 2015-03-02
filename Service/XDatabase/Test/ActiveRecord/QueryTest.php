<?php
namespace X\Service\XDatabase\Test\ActiveRecord;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\ActiveRecord\Query;
/**
 * 
 */
class QueryTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_query() {
        $query = new Query();
        $query->findAll('1=1')
            ->addExpression('*')
            ->setLimit(10)
            ->setOffset(100)
            ->addOrder('id')
            ->addOrder('time')
            ->addTable('table1');
        $sql = "SELECT * FROM `table1` WHERE 1=1 ORDER BY `id`,`time` LIMIT 10 OFFSET 100";
        $this->assertSame($sql, $query->toString());
        
        $query = new Query();
        $query->find('1=1')
        ->addExpression('*')
        ->setOffset(100)
        ->addOrder('id')
        ->addOrder('time')
        ->addTable('table1');
        $sql = "SELECT * FROM `table1` WHERE 1=1 ORDER BY `id`,`time` LIMIT 1 OFFSET 100";
        $this->assertSame($sql, $query->toString());
    }
}