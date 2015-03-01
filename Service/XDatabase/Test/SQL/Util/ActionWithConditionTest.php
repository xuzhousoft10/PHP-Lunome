<?php
namespace X\Service\XDatabase\Test\SQL\Util;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Action\Select;
use X\Service\XDatabase\Core\SQL\Func\Rand;
use X\Service\XDatabase\Core\Util\Exception;
/**
 * 
 */
class ActionWithConditionTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_actionWithCondition() {
        $select = new Select();
        $select->from('table1')->where('1=1')->orderBy('created_at', 'ASC')->orderBy(new Rand())->limit(1)->offset(1);
        $query = "SELECT * FROM `table1` WHERE 1=1 ORDER BY `created_at` ASC,RAND() LIMIT 1 OFFSET 1";
        $this->assertSame($query, $select->toString());
        
        $select = new Select();
        try {
            $select->from('table1')->limit('non-integer-value');
            $this->fail('An exception should be throwed if set a non integer value to limit() method.');
        } catch ( Exception $e ){}
        
        $select = new Select();
        try {
            $select->from('table1')->offset('non-integer-value');
            $this->fail('An exception should be throwed if set a non integer value to offset() method.');
        } catch ( Exception $e ){}
    }
}