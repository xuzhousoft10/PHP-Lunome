<?php
namespace X\Service\XDatabase\Test\SQL\Action;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Action\Update;
use X\Service\XDatabase\Core\Util\Exception;

/**
 * 
 */
class UpdateTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_truncate() {
        $update = new Update();
        $sql = $update->table('table')->values(array('col1'=>'v1', 'col2'=>'v2'))->toString();
        $this->assertSame('UPDATE `table` SET `col1`=\'v1\',`col2`=\'v2\'', $sql);
        
        $update = new Update();
        try{
            $update->values(array());
            $this->fail('An exception should be throwed if updated value is empty.');
        } catch( Exception $e ){}
        
        $update = new Update();
        try{
            $update->values(array('col1'=>'v1'))->toString();
            $this->fail('An exception should be throwed if did not set the table name.');
        } catch( Exception $e ){}
    }
}