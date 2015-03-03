<?php
namespace X\Service\XDatabase\Test\SQL\Action;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Action\Insert;
use X\Service\XDatabase\Core\Util\Exception;
use X\Service\XDatabase\Core\SQL\Util\DefaultValue;
use X\Service\XDatabase\Core\SQL\Util\Expression;
/**
 * 
 */
class InsertTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_insert() {
        $insert = new Insert();
        $sql = $insert->into('table1')->values(array(1,2))->toString();
        $this->assertSame('INSERT INTO `table1`  VALUES (\'1\',\'2\')', $sql);
        
        $insert = new Insert();
        $sql = $insert->into('table1')->values(array('col_1'=>2, 'col_2'=>3))->toString();
        $this->assertSame('INSERT INTO `table1` (`col_1`,`col_2`) VALUES (\'2\',\'3\')', $sql);
        
        $insert = new Insert();
        $sql = $insert->into('table1')->values(array(new DefaultValue(), new Expression('NOW()')))->toString();
        $this->assertSame('INSERT INTO `table1`  VALUES (DEFAULT,NOW())', $sql);
        
        $insert = new Insert();
        try {
            $insert->values(array());
            $this->fail('An exception shuld be throwed if try to insert empty data.');
        } catch ( Exception $e ){}
        
        $insert = new Insert();
        try {
            $insert->values(array('1'))->toString();
            $this->fail('An exception shuld be throwed if there is no table name in insert.');
        } catch ( Exception $e ){}
    }
}