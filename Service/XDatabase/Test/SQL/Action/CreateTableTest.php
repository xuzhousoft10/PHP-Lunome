<?php
namespace X\Service\XDatabase\Test\SQL\Action;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Action\CreateTable;
use X\Service\XDatabase\Core\Util\Exception;
/**
 * 
 */
class CreateTableTest extends ServiceTestCase {
    /**
     * @var CreateTable
     */
    private $createTable = null;
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Test\Util\ServiceTestCase::setUp()
     */
    protected function setUp() {
        parent::setUp();
        $this->createTable = new CreateTable();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\TestCase\ServiceTestCase::tearDown()
     */
    protected function tearDown() {
        $this->createTable = null;
        parent::tearDown();
    }
    
    /**
     * 
     */
    public function test_columns( ) {
        $columns = array('col_1'=>'INT UNSIGNED NOT NULL', 'col_2'=>'VARCHAR(32)');
        $sql = $this->createTable->columns($columns)->primaryKey('col_1')->name('table1')->toString();
        $expected = 'CREATE TABLE `table1` ( `col_1` INT UNSIGNED NOT NULL,`col_2` VARCHAR(32) , PRIMARY KEY (`col_1`) )';
        $this->assertSame($expected, $sql);
        
        try {
            $this->createTable->columns(array());
            $this->fail('An exception should be throwed if try to create table without columns.');
        } catch( Exception $e ){}
    }
    
    /**
     * 
     */
    public function test_primaryKey( ) {
        $columns = array('col_1'=>'INT UNSIGNED NOT NULL', 'col_2'=>'VARCHAR(32)');
        $sql = $this->createTable->columns($columns)->name('table1')->toString();
        $expected = 'CREATE TABLE `table1` ( `col_1` INT UNSIGNED NOT NULL,`col_2` VARCHAR(32) )';
        $this->assertSame($expected, $sql);
    }
    
    /**
     * 
     */
    public function test_noTableNameSetted() {
        $columns = array('col_1'=>'INT UNSIGNED NOT NULL', 'col_2'=>'VARCHAR(32)');
        try {
            $sql = $this->createTable->columns($columns)->primaryKey('col_1')->toString();
            $this->fail('An exception should be throwed if try to create table without a table name.');
        } catch( Exception $e ){}
    }
}