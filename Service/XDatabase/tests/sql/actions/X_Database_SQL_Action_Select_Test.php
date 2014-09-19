<?php
/**
 * SQLBuilderActionSelectTest.php
 * 
 * @author Michael Luthor <michael.the.randiae@gmail.com>
 * @version $Id$
 */

/**
 * SQLBuilderActionSelect test case.
 */
class X_Database_SQL_Action_Select_Test extends PHPUnit_Framework_TestCase {
    
    /**
     *
     * @var SQLBuilderActionSelect
     */
    private $SQLBuilderActionSelect;
    
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
     * Tests SQLBuilderActionSelect->filter()
     */
    public function testFilter() {
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT DISTINCT * FROM `table1`";
        $actual = $select->from('table1')->filter(X\Database\SQL\Action\Select::FILTER_DISTINCT)->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionSelect->columns()
     */
    public function testColumns() {
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT `col1`,`col2` FROM `table`";
        $actual = $select->columns( 'col1','col2')->from('table')->toString();
        $this->assertEquals($expected, $actual);
        
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT `col1` AS `acol1`,`col2` AS `acol2` FROM `table`";
        $actual = $select->columns( array('acol1'=>'col1','acol2'=>'col2') )->from('table')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionSelect->expressions()
     */
    public function testExpressions() {
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT COUNT(*),COUNT(*)";
        $actual = $select->expressions( new X\Database\SQL\Func\Count(), new X\Database\SQL\Func\Count() )->toString();
        $this->assertEquals($expected, $actual);
        
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT COUNT(*) AS `count`,COUNT(*) AS `max`";
        $actual = $select->expressions( array('count'=>new X\Database\SQL\Func\Count(), 'max'=>new X\Database\SQL\Func\Count()) )->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionSelect->from()
     */
    public function testFrom() {
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1`,`table2`";
        $actual = $select->from('table1', 'table2')->toString();
        $this->assertEquals($expected, $actual);
        
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1` AS `tbl1`,`table2` AS `tbl2`";
        $actual = $select->from(array('tbl1'=>'table1', 'tbl2'=>'table2'))->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionSelect->groupBy()
     */
    public function testGroupBy() {
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1` GROUP BY `id`";
        $actual = $select->from('table1')->groupBy('id')->toString();
        $this->assertEquals($expected, $actual);
        
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1` GROUP BY `id`,`name`";
        $actual = $select->from('table1')->groupBy('id')->groupBy('name')->toString();
        $this->assertEquals($expected, $actual);
        
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1` GROUP BY `id`,`id` ASC";
        $actual = $select->from('table1')->groupBy(array('id'), array('id', 'ASC') )->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionSelect->having()
     */
    public function testHaving() {
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1` GROUP BY `id` HAVING `id` = '1'";
        $actual = $select->from('table1')->groupBy('id')->having(X\Database\SQL\Condition\Builder::build(array('id'=>1)))->toString();
        $this->assertEquals($expected, $actual);
        
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1` GROUP BY `id` HAVING `col1` = '1' AND `col2` = '2'";
        $actual = $select->from('table1')->groupBy('id')->having(array('col1'=>1, 'col2'=>2))->toString();
        $this->assertEquals($expected, $actual);
        
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1` GROUP BY `id` HAVING col1=>1 && col2=>2";
        $actual = $select->from('table1')->groupBy('id')->having("col1=>1 && col2=>2")->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionSelect->intoOutFile()
     */
    public function testIntoOutFile() {
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1` INTO OUTFILE '1.txt'";
        $actual = $select->from('table1')->intoOutFile('1.txt')->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionSelect->intoDumpFile()
     */
    public function testIntoDumpFile() {
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1` INTO DUMPFILE '1.txt'";
        $actual = $select->from('table1')->intoDumpFile( '1.txt' )->toString();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests SQLBuilderActionSelect->intoVar()
     */
    public function testIntoVar() {
        $select = X\Database\SQL\SQLBuilder::build()->select();
        $expected = "SELECT * FROM `table1` INTO varname1,varname2";
        $actual = $select->from('table1')->intoVar('varname1', 'varname2')->toString();
        $this->assertEquals($expected, $actual);
    }
}