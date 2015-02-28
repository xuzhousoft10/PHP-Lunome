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
}