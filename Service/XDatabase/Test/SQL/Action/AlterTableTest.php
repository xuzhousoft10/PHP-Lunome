<?php
namespace X\Service\XDatabase\Test\SQL\Action;
/**
 * 
 */
use X\Core\Util\TestCase\ServiceTestCase;
use X\Service\XDatabase\Core\SQL\Action\AlterTable;
use X\Service\XDatabase\Core\Util\Exception;

/**
 * 
 */
class AlterTableTest extends ServiceTestCase {
    /**
     * @return string
     */
    protected function getServiceClass() {
        return 'X\\Service\\XDatabase\\Service';
    }
    
    /**
     * @var AlterTable
     */
    private $alterTable = null;
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\TestCase\ManagerTestCase::setUp()
     */
    protected function setUp() {
        parent::setUp();
        
        $this->manager->getDatabaseManager()->register('test-default', array (
          'dsn' => 'mysql:host=localhost;dbname=test',
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
        ));
        $this->manager->getDatabaseManager()->switchTo('test-default');
        
        $this->alterTable = new AlterTable();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\TestCase\ServiceTestCase::tearDown()
     */
    protected function tearDown() {
        $this->alterTable = null;
        parent::tearDown();
    }
    
    /**
     * 
     */
    public function test_addColumn() {
        $sql = $this->alterTable->name('table1')->addColumn('test_col', 'INT NOT NULL')->toString();
        $this->assertSame('ALTER TABLE `table1` ADD `test_col` INT NOT NULL', $sql);
    }
    
    /**
     * 
     */
    public function test_addIndex( ) {
        $sql = $this->alterTable->name('table1')->addIndex('test_index', array('id','test_col'))->toString();
        $this->assertSame('ALTER TABLE `table1` ADD INDEX `test_index` (`id`,`test_col`)', $sql);
        
        try {
            $this->alterTable->name('table1')->addIndex('test_index_1', array());
            $this->fail('An exception should be throwed if try to add index without columns.');
        } catch ( Exception $e ){}
    }
    
    /**
     * 
     */
    public function test_addPrimaryKey() {
        $sql = $this->alterTable->addPrimaryKey(array('id', 'test_col'))->name('table1')->toString();
        $this->assertSame('ALTER TABLE `table1` ADD PRIMARY KEY (`id`,`test_col`)', $sql);
        
        try {
            $this->alterTable->name('table1')->addPrimaryKey(array());
            $this->fail('An exception should be throwed if try to add index without columns.');
        } catch ( Exception $e ){}
    }
    
    /**
     * 
     */
    public function test_addUnique() {
        $sql = $this->alterTable->addUnique(array('id', 'test_col'))->name('table1')->toString();
        $this->assertSame('ALTER TABLE `table1` ADD UNIQUE ( `id`,`test_col` )', $sql);
        
        try {
            $this->alterTable->name('table1')->addUnique(array());
            $this->fail('An exception should be throwed if try to add unique without columns.');
        } catch ( Exception $e ){}
    }
    
    /**
     * 
     */
    public function test_changeColumn( ) {
        $sql = $this->alterTable->changeColumn('test_col', 'test_col_new', 'INT')->name('table1')->toString();
        $this->assertSame('ALTER TABLE `table1` CHANGE COLUMN `test_col` `test_col_new` INT', $sql);
    }
    
    /**
     * 
     */
    public function test_dropColumn( ) {
        $sql = $this->alterTable->dropColumn('test_col_new')->name('table1')->toString();
        $this->assertSame('ALTER TABLE `table1` DROP COLUMN `test_col_new`', $sql);
    }
    
    /**
     * 
     */
    public function test_dropPrimaryKey() {
        $sql = $this->alterTable->dropPrimaryKey()->name('table1')->toString();
        $this->assertSame('ALTER TABLE `table1` DROP PRIMARY KEY', $sql);
    }
    
    /**
     * 
     */
    public function test_dropIndex( ) {
        $sql = $this->alterTable->dropIndex('index_1')->name('table1')->toString();
        $this->assertSame('ALTER TABLE `table1` DROP INDEX `index_1`', $sql);
    }
}