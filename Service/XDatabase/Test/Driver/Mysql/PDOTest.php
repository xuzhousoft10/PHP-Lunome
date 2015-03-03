<?php
namespace X\Service\XDatabase\Test\Driver\Mysql;
/**
 * 
 */
use X\Core\Util\TestCase;
use X\Service\XDatabase\Core\Driver\Mysql\PDO;
use X\Service\XDatabase\Core\Util\Exception;

/**
 * 
 */
class PDOTest extends TestCase {
    /**
     * 
     */
    public function test_PDODriver () {
        $config = array (
          'dsn' => 'mysql:host=localhost;dbname=test',
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
        );
        
        $pdo = new PDO($config);
        $this->assertSame('`table_name`', $pdo->quoteTableName('table_name'));
        $this->assertSame('`col_name`', $pdo->quoteColumnName('col_name'));
        $this->assertSame('\'`test\"\\\'+---/**/`\'', $pdo->quote('`test"\'+---/**/`'));
        
        $pdo->exec('CREATE  TABLE `test`.`test_table` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`));');
        $this->assertContains('test_table', $pdo->getTables());
        $pdo->exec('INSERT INTO `test`.`test_table` (`id`) VALUES (\'\')');
        $this->assertSame(1, $pdo->getLastInsertId());
        $pdo->exec('DROP TABLE `test`.`test_table`');
        
        try {
            $pdo->query('this-is-a-invalidate-sql-query.');
            $this->fail('An exception should be throwed if there is any error in query.');
        } catch ( Exception $e ){}
        
        try {
            $pdo->exec('this-is-a-invalidate-sql-query.');
            $this->fail('An exception should be throwed if there is any error in query.');
        } catch ( Exception $e ){}
        
        try {
            $pdo = new PDO(array());
            $this->fail('An exception should be throwed if there is any error in configuration.');
        } catch ( Exception $e ){}
        
    }
}