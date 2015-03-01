<?php
namespace X\Service\XDatabase\Test\SQL\Condition;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Condition\Builder;
use X\Core\Test\Fixture\Util\ConfigurationArray;
use X\Service\XDatabase\Core\SQL\Condition\Condition;
use X\Service\XDatabase\Core\SQL\Util\Expression;
use X\Service\XDatabase\Core\SQL\Condition\Connector;
/**
 * 
 */
class BuildTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_build() {
        $this->assertSame('1=1', Builder::build('1=1')->toString());
    }
    
    /**
     * 
     */
    public function test_addCondition(){
        $condition = Builder::build()->addCondition(array('col1'=>'val1', 'col2'=>'val2'));
        $this->assertSame("`col1` = 'val1' AND `col2` = 'val2'", $condition->toString());
        
        $condition = new ConfigurationArray();
        $condition->merge(array('val1', 'val2'));
        $condition = Builder::build()->addCondition(array('col1'=>$condition));
        $this->assertSame("`col1` IN ('val1','val2')", $condition->toString());
        
        $condition = new Condition('col', Condition::OPERATOR_EQUAL, 'value1');
        $condition = Builder::build()->addCondition($condition);
        $this->assertSame("`col` = 'value1'", $condition->toString());
        
        $condition = Builder::build()->addCondition(new Expression('1=2'));
        $this->assertSame("1=2", $condition->toString());
    }
    
    /**
     * 
     */
    public function test_addSigleCondition(){
        $condition = Builder::build()->addSigleCondition('col', Condition::OPERATOR_EQUAL, '1');
        $this->assertSame("`col` = '1'", $condition->toString());
    }
    
    /**
     * 
     */
    public function test_addConnector() {
        $condition = Builder::build()->addCondition('1=1');
        $condition->addConnector(Connector::CONNECTOR_AND);
        $condition->addCondition('2=2');
        $this->assertSame("1=1 AND 2=2", $condition->toString());
    }
    
    /**
     * 
     */
    public function test_operators() {
        $condition = Builder::build()->is('col_1', 'value_1');
        $this->assertSame("`col_1` = 'value_1'", $condition->toString());
        
        $condition = Builder::build()->isNot('col_1', 'value_1');
        $this->assertSame("`col_1` <> 'value_1'", $condition->toString());
        
        $condition = Builder::build()->equals('col_1', 'value_1');
        $this->assertSame("`col_1` = 'value_1'", $condition->toString());
        
        $condition = Builder::build()->notEquals('col_1', 'value_1');
        $this->assertSame("`col_1` <> 'value_1'", $condition->toString());
        
        $condition = Builder::build()->greaterThan('col_1', 'value_1');
        $this->assertSame("`col_1` > 'value_1'", $condition->toString());
        
        $condition = Builder::build()->greaterOrEquals('col_1', 'value_1');
        $this->assertSame("`col_1` >= 'value_1'", $condition->toString());
        
        $condition = Builder::build()->lessThan('col_1', 'value_1');
        $this->assertSame("`col_1` < 'value_1'", $condition->toString());
        
        $condition = Builder::build()->lessOrEquals('col_1', 'value_1');
        $this->assertSame("`col_1` <= 'value_1'", $condition->toString());
        
        $condition = Builder::build()->like('col_1', 'value_1');
        $this->assertSame("`col_1` LIKE 'value_1'", $condition->toString());
        
        $condition = Builder::build()->startWith('col_1', 'value_1');
        $this->assertSame("`col_1` LIKE 'value_1%%'", $condition->toString());
        
        $condition = Builder::build()->endWith('col_1', 'value_1');
        $this->assertSame("`col_1` LIKE '%%value_1'", $condition->toString());
        
        $condition = Builder::build()->includes('col_1', 'value_1');
        $this->assertSame("`col_1` LIKE '%%value_1%%'", $condition->toString());
        
        $condition = Builder::build()->between('col_1', 'value_1', 'value_2');
        $this->assertSame("`col_1` BETWEEN 'value_1' AND 'value_2'", $condition->toString());
        
        $condition = Builder::build()->in('col_1', array('value_1', 'value_2', 'value_3'));
        $this->assertSame("`col_1` IN ('value_1','value_2','value_3')", $condition->toString());
        
        $condition = Builder::build()->notIn('col_1', array('value_1', 'value_2', 'value_3'));
        $this->assertSame("`col_1` NOT IN ('value_1','value_2','value_3')", $condition->toString());
        
        $condition = Builder::build()->exists('1=1');
        $this->assertSame("EXISTS ( 1=1 )", $condition->toString());
        
        $condition = Builder::build()->notExists('1=1');
        $this->assertSame("NOT EXISTS ( 1=1 )", $condition->toString());
    }
    
    /**
     * 
     */
    public function test_connector() {
        $condtion = Builder::build()->is('col_1', 'value_1')->andAlso()->is('col_2', 'value_2');
        $this->assertSame("`col_1` = 'value_1' AND `col_2` = 'value_2'", $condtion->toString());
        
        $condtion = Builder::build()->is('col_1', 'value_1')->orThat()->is('col_1', 'value_2');
        $this->assertSame("`col_1` = 'value_1' OR `col_1` = 'value_2'", $condtion->toString());
    }
    
    /**
     * 
     */
    public function test_group() {
        $condition = Builder::build()->groupStart()->is('col_1', 'value_1')->andAlso()->groupEnd();
        $this->assertSame("( `col_1` = 'value_1' )", $condition->toString());
    }
    
    public function test_emptyCondition() {
        $condition = Builder::build();
        $this->assertSame("", $condition->toString());
        
        $condition = Builder::build()->addCondition('')->addCondition('');
        $this->assertSame('', $condition->toString());
    }
}