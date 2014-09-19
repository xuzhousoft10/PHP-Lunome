<?php
/**
 * X\Database\SQL\Condition\BuilderTest.php
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */

/**
 * X\Database\SQL\Condition\Builder test case.
 */
class X_Database_SQL_Condition_Builder_Test extends PHPUnit_Framework_TestCase {
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder::setNameStyle()
     */
    public function testSetNameStyle() {
        X\Database\SQL\Condition\Builder::setNameStyle(X\Database\SQL\Condition\Builder::NAME_STYLE_CAMEL);
        /* @var $condition X\Database\SQL\Condition\Builder */
        $condition = X\Database\SQL\Condition\Builder::build()->columnAIs('val');
        $expected = "`columnA` = 'val'";
        $actual = $condition->toString();
        $this->assertEquals($expected, $actual);
        
        X\Database\SQL\Condition\Builder::setNameStyle(X\Database\SQL\Condition\Builder::NAME_STYLE_SNAKE);
        /* @var $condition X\Database\SQL\Condition\Builder */
        $condition = X\Database\SQL\Condition\Builder::build()->columnAIs('val');
        $expected = "`column_a` = 'val'";
        $actual = $condition->toString();
        $this->assertEquals($expected, $actual);
        
        X\Database\SQL\Condition\Builder::setNameStyle(X\Database\SQL\Condition\Builder::NAME_STYLE_SNAKE);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->__call()
     */
    public function test__call() {
        /* @var $condition X\Database\SQL\Condition\Builder */
        $condition = X\Database\SQL\Condition\Builder::build()->columnAIs('val');
        $expected = "`column_a` = 'val'";
        $actual = $condition->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder::build()
     */
    public function testBuild() {
        $builder = X\Database\SQL\Condition\Builder::build();
        $this->assertInstanceOf('X\Database\SQL\Condition\Builder', $builder);
        
        $expected = "`col` = 'val'";
        $actual = X\Database\SQL\Condition\Builder::build(array('col'=>'val'))->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->addCondition()
     */
    public function testAddCondition() {
        $builder = X\Database\SQL\Condition\Builder::build();
        $expected = "`col` = 'val'";
        $actual = $builder->addCondition(array('col'=>'val'))->toString();
        $this->assertEquals($expected, $actual);
        
        $builder = X\Database\SQL\Condition\Builder::build();
        $expected = "`col` = 'val'";
        $actual = $builder->addCondition("`col` = 'val'")->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->addSigleCondition()
     */
    public function testAddSigleCondition() {
        $builder = X\Database\SQL\Condition\Builder::build();
        $expected = "`col` = 'val'";
        $actual = $builder->addSigleCondition('col', X\Database\SQL\Condition\Condition::OPERATOR_EQUAL, 'val')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->addConnector()
     */
    public function testAddConnector() {
        $builder = X\Database\SQL\Condition\Builder::build();
        $builder->is('col1', 'val1')->addConnector(X\Database\SQL\Condition\Connector::CONNECTOR_OR)->is('col2', 'val2');
        $actual = $builder->toString();
        $expected = "`col1` = 'val1' OR `col2` = 'val2'";
        $this->assertEquals($expected, $actual);
        
        $builder = X\Database\SQL\Condition\Builder::build();
        $builder->is('col1', 'val1')->addConnector(X\Database\SQL\Condition\Connector::CONNECTOR_AND)->is('col2', 'val2');
        $actual = $builder->toString();
        $expected = "`col1` = 'val1' AND `col2` = 'val2'";
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->is()
     */
    public function testIs() {
        $expected = "`col` = 'val'";
        $actual = X\Database\SQL\Condition\Builder::build()->is('col', 'val')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->isNot()
     */
    public function testIsNot() {
        $expected = "`col` <> 'val'";
        $actual = X\Database\SQL\Condition\Builder::build()->isNot('col', 'val')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->equals()
     */
    public function testEquals() {
        $expected = "`col` = 'val'";
        $actual = X\Database\SQL\Condition\Builder::build()->equals('col', 'val')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->notEquals()
     */
    public function testNotEquals() {
        $expected = "`col` <> 'val'";
        $actual = X\Database\SQL\Condition\Builder::build()->notEquals('col', 'val')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->greaterThan()
     */
    public function testGreaterThan() {
        $expected = "`col` > '1'";
        $actual = X\Database\SQL\Condition\Builder::build()->greaterThan('col', 1)->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->greaterOrEquals()
     */
    public function testGreaterOrEquals() {
        $expected = "`col` >= '1'";
        $actual = X\Database\SQL\Condition\Builder::build()->greaterOrEquals('col', 1)->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->lessThan()
     */
    public function testLessThan() {
        $expected = "`col` < '1'";
        $actual = X\Database\SQL\Condition\Builder::build()->lessThan('col', 1)->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->lessOrEquals()
     */
    public function testLessOrEquals() {
        $expected = "`col` <= '1'";
        $actual = X\Database\SQL\Condition\Builder::build()->lessOrEquals('col', 1)->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->like()
     */
    public function testLike() {
        $expected = "`col` LIKE 'val'";
        $actual = X\Database\SQL\Condition\Builder::build()->like('col', 'val')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->startWith()
     */
    public function testStartWith() {
        $expected = "`col` LIKE 'val%%'";
        $actual = X\Database\SQL\Condition\Builder::build()->startWith('col', 'val')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->endWith()
     */
    public function testEndWith() {
        $expected = "`col` LIKE '%%val'";
        $actual = X\Database\SQL\Condition\Builder::build()->endWith('col', 'val')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->between()
     */
    public function testBetween() {
        $expected = "`col` BETWEEN ('1', '100')";
        $actual = X\Database\SQL\Condition\Builder::build()->between('col', 1, 100)->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->in()
     */
    public function testIn() {
        $expected = "`col` IN ('1','2','3','4')";
        $actual = X\Database\SQL\Condition\Builder::build()->in('col', array(1,2,3,4))->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->notIn()
     */
    public function testNotIn() {
        $expected = "`col` NOT IN ('1','2','3','4')";
        $actual = X\Database\SQL\Condition\Builder::build()->notIn('col', array(1,2,3,4))->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->andAlso()
     */
    public function testAndAlso() {
        $expected = "`col1` = 'val1' AND `col2` = 'val2'";
        $builder = X\Database\SQL\Condition\Builder::build();
        $actual = $builder->is('col1', 'val1')->andAlso()->is('col2', 'val2')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->orThat()
     */
    public function testOrThat() {
        $expected = "`col1` = 'val1' OR `col2` = 'val2'";
        $builder = X\Database\SQL\Condition\Builder::build();
        $actual = $builder->is('col1', 'val1')->orThat()->is('col2', 'val2')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->groupStart()
     */
    public function testGroupStart() {
        $expected = "`col1` = 'val1' OR ( `col2` = 'val2' )";
        $builder = X\Database\SQL\Condition\Builder::build();
        $actual = $builder->is('col1', 'val1')->orThat()->groupStart()->is('col2', 'val2')->groupEnd()->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->groupEnd()
     */
    public function testGroupEnd() {
        $expected = "`col1` = 'val1' OR ( `col2` = 'val2' )";
        $builder = X\Database\SQL\Condition\Builder::build();
        $actual = $builder->is('col1', 'val1')->orThat()->groupStart()->is('col2', 'val2')->groupEnd()->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests X\Database\SQL\Condition\Builder->toString()
     */
    public function testToString() {
        $expected = "`col1` = 'val1' OR ( `col2` = 'val2' )";
        $builder = X\Database\SQL\Condition\Builder::build();
        $actual = $builder->is('col1', 'val1')->orThat()->groupStart()->is('col2', 'val2')->groupEnd()->toString();
        $this->assertEquals($expected, $actual);
    }
}