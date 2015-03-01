<?php
namespace X\Service\XDatabase\Test\Table;
/**
 * 
 */
use X\Core\X;
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\Table\Manager;
use X\Service\XDatabase\Service;
/**
 * 
 */
class ManagerTest extends ServiceTestCase {
    /**
     * 
     */
    public function test_getTables() {
        /* @var $service Service */
        $service = X::system()->getServiceManager()->get(Service::getServiceName());
        $db = $service->getDatabaseManager()->get();
        
        $column = array(
            'col_1' => 'INT UNSIGNED',
            'col_2' => 'VARCHAR(32) NOT NULL',
        );
        Manager::create('test_table_1', $column, 'col_1');
        
        $tables = Manager::getTables();
        $this->assertContains('test_table_1', $tables);
        
        $testTable = Manager::open('test_table_1');
        $testTable->insert(array('col_1'=>'1','col_2'=>'value2'));
        $count = $db->query('SELECT COUNT(*) as r_count FROM test_table_1');
        $count = $count[0];
        $this->assertSame(1, (int)$count['r_count']);
        
        $testTable->truncate();
        $count = $db->query('SELECT COUNT(*) as r_count FROM test_table_1');
        $count = $count[0];
        $this->assertSame(0, (int)$count['r_count']);
        
        $testTable->rename('test_table_another_name');
        $tables = Manager::getTables();
        $this->assertContains('test_table_another_name', $tables);
        
        $testTable->addColumn('col_3', 'INT NOT NULL');
        $info = $testTable->getInformation();
        $this->assertSame(3, count($info));
        $this->assertSame('col_3', $info[2]['Field']);
        
        $testTable->dropColumn('col_3');
        $info = $testTable->getInformation();
        $this->assertSame(2, count($info));
        $isCol3Exists = false;
        foreach ( $info as $columnInfo ) {
            if ( 'col_3' === $columnInfo['Field'] ) {
                $isCol3Exists = true;
            }
        }
        $this->assertFalse($isCol3Exists);
        
        $testTable->changeColumn('col_2', 'VARCHAR(10)');
        $info = $testTable->getInformation();
        $this->assertSame('varchar(10)', $info[1]['Type']);
        
        $testTable->addIndex('index_1', array('col_1', 'col_2'));
        $indexes = $db->query('SHOW INDEX FROM test_table_another_name');
        $isIndexExists = false;
        foreach ( $indexes as $indexInfo ) {
            if ( 'index_1' === $indexInfo['Key_name'] ) {
                $isIndexExists = true;
            }
        }
        $this->assertTrue($isIndexExists);
        
        $testTable->dropIndex('index_1');
        $indexes = $db->query('SHOW INDEX FROM test_table_another_name');
        $isIndexExists = false;
        foreach ( $indexes as $indexInfo ) {
            if ( 'index_1' === $indexInfo['Key_name'] ) {
                $isIndexExists = true;
            }
        }
        $this->assertFalse($isIndexExists);
        
        $testTable->dropPrimaryKey('col_1');
        $indexes = $db->query('SHOW INDEX FROM test_table_another_name');
        $this->assertEmpty($indexes);
        
        $testTable->addPrimaryKey(array('col_2'));
        $indexes = $db->query('SHOW INDEX FROM test_table_another_name');
        $this->assertSame('PRIMARY', $indexes[0]['Key_name']);
        
        $testTable->addUnique(array('col_1', 'col_2'));
        $indexes = $db->query('SHOW INDEX FROM test_table_another_name');
        $this->assertSame(3, count($indexes));
        
        $testTable->drop();
        $tables = Manager::getTables();
        $this->assertNotContains('test_table_another_name', $tables);
    }
}