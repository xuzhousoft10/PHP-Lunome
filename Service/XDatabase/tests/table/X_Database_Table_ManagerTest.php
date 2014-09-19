<?php
/**
 * SQLBuilderTest.php
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */
/**
 * TableManager test case.
 */
class X_Database_Table_ManagerTest extends PHPUnit_Framework_TestCase {
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
     * 
     * @param unknown $query
     */
    protected function execQueryWithoutResutl( $query ) {
        return \X\Database\Management::getManager()->getDb()->exec($query);
    }
    
    /**
     * 
     * @param unknown $query
     */
    protected function execQueryWithResult( $query ) {
        return \X\Database\Management::getManager()->getDb()->query($query);
    }
    
    /**
     * Tests TableManager::create()
     */
    public function testCreate() {
        \X\Database\Table\Management::open('table_test')->drop();
        $table = \X\Database\Table\Management::create('table_test', array(
            'col' => 'INT(11)'
        ));
        $this->assertInstanceOf('\X\Database\Table\Management', $table);
        $this->assertTrue($table->drop());
    }
    
    /**
     * Tests TableManager::open()
     */
    public function testOpen() {
        $table = \X\Database\Table\Management::open('table');
        $this->assertInstanceOf('\X\Database\Table\Management', $table);
    }
    
    /**
     * Tests TableManager->drop()
     */
    public function testDrop() {
        \X\Database\Table\Management::open('table_test_drop')->drop();
        $table = \X\Database\Table\Management::create('table_test_drop', array(
            'col' => 'INT(11)'
        ));
        $this->assertInstanceOf('\X\Database\Table\Management', $table);
        $this->assertTrue($table->drop());
        $this->assertFalse(\X\Database\Table\Management::open('non-exists-table')->drop());
    }
    
    /**
     * Tests TableManager->truncate()
     */
    public function testTruncate() {
        $table = \X\Database\Table\Management::create('table_test_truncate', array('col'=>'INT(11)'));
        $table->insert(array('1'));
        $table->insert(array('2'));
        $table->insert(array('3'));
        $result = $this->execQueryWithResult('SELECT COUNT(*) AS `count` FROM `table_test_truncate`');
        $this->assertEquals(3, $result[0]['count']*1);
        $table->truncate();
        $result = $this->execQueryWithResult('SELECT COUNT(*) AS `count` FROM `table_test_truncate`');
        $this->assertEquals(0, $result[0]['count']*1);
        $table->drop();
    }
    
    /**
     * Tests TableManager->rename()
     */
    public function testRename() {
        $table = \X\Database\Table\Management::create('table_test_rename', array('col'=>'INT(11)'));
        $result = $this->execQueryWithResult('SELECT COUNT(*) AS `count` FROM `table_test_rename`');
        $this->assertEquals(0, $result[0]['count']*1);
        $table->rename('table_test_rename_new_name');
        $result = $this->execQueryWithResult('SELECT COUNT(*) AS `count` FROM `table_test_rename_new_name`');
        $this->assertEquals(0, $result[0]['count']*1);
        $table->drop();
        $this->assertFalse(\X\Database\Table\Management::open('table_test_rename')->drop());
    }
    
    /**
     * Tests TableManager->addColumn()
     */
    public function testAddColumn() {
        $table = \X\Database\Table\Management::create('table_test', array('col1'=>'INT(11)'));
        $table->addColumn('col2', 'INT(11)');
        $table->insert(array(1,1));
        $result = $this->execQueryWithResult('SELECT * FROM `table_test`');
        $this->assertArrayHasKey('col2', $result[0]);
        $table->drop();
    }
    
    /**
     * Tests TableManager->dropColumn()
     */
    public function testDropColumn() {
        $table = \X\Database\Table\Management::create('table_test', array('col1'=>'INT', 'col2'=>'INT'));
        $table->insert(array(1,1));
        $table->dropColumn('col2');
        $result = $this->execQueryWithResult('SELECT * FROM `table_test`');
        $this->assertArrayNotHasKey('col2', $result[0]);
        $table->drop();
    }
    
    /**
     * Tests TableManager->renameColumn()
     */
    public function testRenameColumn() {
        $table = \X\Database\Table\Management::create('table_test', array('col1'=>'INT'));
        $table->insert(array(1));
        $table->renameColumn('col1', 'col2');
        $result = $this->execQueryWithResult('SELECT * FROM `table_test`');
        $this->assertArrayNotHasKey('col2', $result[0]);
        $table->drop();
    }
    
    /**
     * Tests TableManager->changeColumn()
     */
    public function testChangeColumn() {
        $table = \X\Database\Table\Management::create('table_test', array('col1'=>'INT'));
        $table->insert(array(1));
        $table->changeColumn('col1', 'DATETIME');
        $result = $this->execQueryWithResult('SELECT * FROM `table_test`');
        $this->assertEquals('0000-00-00 00:00:00', $result[0]['col1']);
        $table->drop();
    }
    
    /**
     * Tests TableManager->addIndex()
     */
    public function testAddIndex() {
        \X\Database\Table\Management::open('table_test')->drop();
        $table = \X\Database\Table\Management::create('table_test', array('col1'=>'INT'));
        $this->assertFalse($table->dropIndex('non-exists-index'));
        $table->addIndex('test_index', array('col1'));
        $this->assertInstanceOf('\X\Database\Table\Management', $table->dropIndex('test_index'));
        $table->drop();
    }
    
    /**
     * Tests TableManager->dropIndex()
     */
    public function testDropIndex() {
        $table = \X\Database\Table\Management::create('table_test', array('col1'=>'INT'));
        $this->assertFalse($table->dropIndex('non-exists-index'));
        $table->addIndex('test_index', array('col1'));
        $this->assertInstanceOf('\X\Database\Table\Management', $table->dropIndex('test_index'));
        $table->drop();
    }
    
    /**
     * Tests TableManager->addPrimaryKey()
     */
    public function testAddPrimaryKey() {
        $table = \X\Database\Table\Management::create('table_test', array('col1'=>'INT'));
        $this->assertInstanceOf('\X\Database\Table\Management', $table->addPrimaryKey(array('col1')));
        $this->assertFalse($table->addPrimaryKey(array('col1')));
        $table->drop();
    }
    
    /**
     * Tests TableManager->dropPrimaryKey()
     */
    public function testDropPrimaryKey() {
        $table = \X\Database\Table\Management::create('table_test', array('col1'=>'INT'));
        $this->assertFalse($table->dropPrimaryKey());
        $this->assertInstanceOf('\X\Database\Table\Management', $table->addPrimaryKey(array('col1')));
        $this->assertInstanceOf('\X\Database\Table\Management', $table->dropPrimaryKey());
        $table->drop();
    }
    
    /**
     * Tests TableManager->addUnique()
     */
    public function testAddUnique() {
        $table = \X\Database\Table\Management::create('table_test', array('col1'=>'INT'));
        $this->assertNotEquals(false, $table->insert(array(1)));
        $table->addUnique(array('col1'));
        $this->assertFalse($table->insert(array(1)));
        $this->assertNotEquals(false, $table->insert(array(2)));
        $table->drop();
    }
}

