<?php
/**
 * SQLBuilderTest.php
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */

/**
 * TestHelperTestMigrate
 */
class TestHelperTestMigrate extends \X\Database\Migration\Migrate {
    /**
     * (non-PHPdoc)
     * @see \X\Database\Migration\Migrate::up()
     */
    public function up() {
        $this->createTable('migrate_test_up', array('col1'=>'INT'));
        $this->addColumn('migrate_test_up', 'col2', 'INT');
        $this->addColumn('migrate_test_up', 'col3', 'INT');
        $this->changeColumn('migrate_test_up', 'col3', 'DATETIME');
        $this->insert('migrate_test_up', array(1,2,3));
        $this->addIndex('migrate_test_up', 'migrate_index_up', array('col1'));
        $this->setPrimaryKey('migrate_test_up', array('col1'));
        $this->renameTable('migrate_test_up', 'migrate_test_up_new');
        
        $this->createTable('migrate_test_up_2', array('col1'=>'INT'));
        $this->insert('migrate_test_up_2', array(1));
    }

    /**
     * (non-PHPdoc)
     * @see \X\Database\Migration\Migrate::down()
     */
    public function down() {
        $this->truncate('migrate_test_up_2');
        $this->dropIndex('migrate_test_up_new', 'migrate_index_up');
        $this->dropColumn('migrate_test_up_new', 'col2');
        $this->dropPrimaryKey('migrate_test_up_new');
        $this->dropTable('migrate_test_up_new');
    }
}
/**
 * Migrate test case.
 */
class X_Database_Migration_MigrateTest extends PHPUnit_Framework_TestCase {
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
     * testMigrate
     */
    public function testMigrate() {
        $migrate = new TestHelperTestMigrate();
        $migrate->up();
        $result = $this->execQueryWithResult('SELECT * FROM migrate_test_up_new');
        $this->assertArrayHasKey('col1', $result[0]);
        $this->assertArrayHasKey('col2', $result[0]);
        $this->assertArrayHasKey('col3', $result[0]);
        $this->assertEquals('0000-00-00 00:00:00', $result[0]['col3']);
        
        $migrate->down();
        $result = $this->execQueryWithResult('SELECT COUNT(*) AS `count` FROM migrate_test_up_2');
        $this->assertEquals(0, $result[0]['count']);
        $result = $this->execQueryWithoutResutl('SELECT * FROM migrate_test_up_new');
        $this->assertFalse($result);
        $this->assertTrue($this->execQueryWithoutResutl('DROP TABLE migrate_test_up_2'));
    }
}

