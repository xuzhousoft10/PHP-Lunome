<?php
namespace X\Service\XDatabase\Test\SQL\Condition;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Condition\Condition;
use X\Service\XDatabase\Core\Util\Exception;
use X\Service\XDatabase\Core\SQL\Func\Count;
use X\Service\XDatabase\Core\SQL\Util\Expression;
/**
 * 
 */
class ConditionTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_Equal_NotEqual_Greater_GreaterOrEqual_Less_LessOrEqual() {
        $condition = new Condition('col', Condition::OPERATOR_EQUAL, 'value1');
        $this->assertSame('`col` = \'value1\'', $condition->toString());
        
        $condition = new Condition('col', Condition::OPERATOR_NOT_EQUAL, 'value1');
        $this->assertSame('`col` <> \'value1\'', $condition->toString());
        
        $condition = new Condition('col', Condition::OPERATOR_GREATER_THAN, 'value1');
        $this->assertSame('`col` > \'value1\'', $condition->toString());
        
        $condition = new Condition('col', Condition::OPERATOR_GREATER_OR_EQUAL, 'value1');
        $this->assertSame('`col` >= \'value1\'', $condition->toString());
        
        $condition = new Condition('col', Condition::OPERATOR_LESS_THAN, 'value1');
        $this->assertSame('`col` < \'value1\'', $condition->toString());
        
        $condition = new Condition('col', Condition::OPERATOR_LESS_OR_EQUAL, 'value1');
        $this->assertSame('`col` <= \'value1\'', $condition->toString());
    }
    
    /**
     * 
     */
    public function test_Like() {
        $condition = new Condition('col', Condition::OPERATOR_LIKE, 'value');
        $this->assertSame('`col` LIKE \'value\'', $condition->toString());
    }
    
    /**
     * 
     */
    public function test_StartWith() {
        $condition = new Condition('col', Condition::OPERATOR_START_WITH, 'value');
        $this->assertSame('`col` LIKE \'value%%\'', $condition->toString());
    }
    
    /**
     *
     */
    public function test_EndWith() {
        $condition = new Condition('col', Condition::OPERATOR_END_WITH, 'value');
        $this->assertSame('`col` LIKE \'%%value\'', $condition->toString());
    }
    
    /**
     *
     */
    public function test_Includes() {
        $condition = new Condition('col', Condition::OPERATOR_INCLUDES, 'value');
        $this->assertSame('`col` LIKE \'%%value%%\'', $condition->toString());
    }
    
    /**
     *
     */
    public function test_Between() {
        $condition = new Condition('col', Condition::OPERATOR_BETWEEN, array('value1', 'value2'));
        $this->assertSame('`col` BETWEEN \'value1\' AND \'value2\'', $condition->toString());
        
        $condition = new Condition('col', Condition::OPERATOR_BETWEEN, null);
        try {
            $condition->toString();
            $this->fail('An exception should be throwed if value is not an array.');
        } catch ( Exception $e ){}
        
        $condition = new Condition('col', Condition::OPERATOR_BETWEEN, array());
        try {
            $condition->toString();
            $this->fail('An exception should be throwed if value is not an array with two elements.');
        } catch ( Exception $e ){}
    }
    
    /**
     * 
     */
    public function test_In() {
        $condition = new Condition('col', Condition::OPERATOR_IN, array('v1', 'v2', 'v3'));
        $this->assertSame("`col` IN ('v1','v2','v3')", $condition->toString());
        
        $condition = new Condition('col', Condition::OPERATOR_IN, new Count());
        $this->assertSame("`col` IN (COUNT(*))", $condition->toString());
        
        $condition = new Condition('col', Condition::OPERATOR_IN, null);
        try {
            $condition->toString();
            $this->fail('An exception should be throwed if value is not invalidate.');
        } catch ( Exception $e ){}
    }
    
    /**
     *
     */
    public function test_NotIn() {
        $condition = new Condition('col', Condition::OPERATOR_NOT_IN, array('v1', 'v2', 'v3'));
        $this->assertSame("`col` NOT IN ('v1','v2','v3')", $condition->toString());
        
        $condition = new Condition('col', Condition::OPERATOR_NOT_IN, new Count());
        $this->assertSame("`col` NOT IN (COUNT(*))", $condition->toString());
        
        $condition = new Condition('col', Condition::OPERATOR_NOT_IN, null);
        try {
            $condition->toString();
            $this->fail('An exception should be throwed if value is not invalidate.');
        } catch ( Exception $e ){}
    }
    
    /**
     * 
     */
    public function test_Exists() {
        $condition = new Condition('col', Condition::OPERATOR_EXISTS, '1=1');
        $this->assertSame('EXISTS ( 1=1 )', $condition->toString());
    }
    
    /**
     *
     */
    public function test_NotExists() {
        $condition = new Condition('col', Condition::OPERATOR_NOT_EXISTS, '1=1');
        $this->assertSame('NOT EXISTS ( 1=1 )', $condition->toString());
    }
    
    /**
     * 
     */
    public function test_quoteValue() {
        $condition = new Condition('col', Condition::OPERATOR_EQUAL, new Expression('NOW()'));
        $this->assertSame('`col` = NOW()', $condition->toString());
    }
    
    /**
     * 
     */
    public function test_quoteColumn() {
        $condition = new Condition('table.col', Condition::OPERATOR_EQUAL, new Expression('NOW()'));
        $this->assertSame('`table`.`col` = NOW()', $condition->toString());
    }
}