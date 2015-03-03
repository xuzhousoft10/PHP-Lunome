<?php
namespace X\Service\XDatabase\Test\SQL\Action;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Action\Select;
use X\Service\XDatabase\Core\SQL\Func\Count;

/**
 * 
 */
class SelectTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_select() {
        $select = new Select();
        $sql = $select->expression('*')->from('table1')->groupBy('id', 'ASC')->having('1=1')->toString();
        $this->assertSame('SELECT * FROM `table1` GROUP BY `id` ASC HAVING 1=1', $sql);
        
        $select = new Select();
        $sql = $select->expression(new Count())->toString();
        $this->assertSame('SELECT COUNT(*)', $sql);
        
        $select = new Select();
        $sql = $select->expression('col_1')->from('table1')->toString();
        $this->assertSame('SELECT `col_1` FROM `table1`', $sql);
        
        $select = new Select();
        $sql = $select->expression('col_1', 'col_alias')->from('table1')->toString();
        $this->assertSame('SELECT `col_1` AS `col_alias` FROM `table1`', $sql);
        
        $select = new Select();
        $sql = $select->from('table1')->toString();
        $this->assertSame('SELECT * FROM `table1`', $sql);
        
        $select = new Select();
        $sql = $select->from('table1', 'table_alias')->toString();
        $this->assertSame('SELECT * FROM `table1` AS `table_alias`', $sql);
    }
}