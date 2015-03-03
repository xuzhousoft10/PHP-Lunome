<?php
namespace X\Service\XDatabase\Test\Database;

/**
 * 
 */
use X\Core\Util\TestCase;
use X\Service\XDatabase\Core\Database\Database;
use X\Service\XDatabase\Core\Util\Exception;

/**
 * 
 */
class DatabaseTest extends TestCase {
    /**
     * 
     */
    public function test_database() {
        $config = array (
            // 'dsn' => 'mysql:host=localhost;dbname=test', /*  No dsn information here, */
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        );
        
        $database = new Database($config);
        try {
            $database->exec('SHOW DATABASES');
            $this->fail('An exception should be throwed if there is no dsn in configuraiton array.');
        } catch ( Exception $e ){}
        
        $config = array (
            'dsn' => 'sqlite:test.db',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        );
        $database = new Database($config);
        try {
            $database->exec('SHOW DATABASES');
            $this->fail('An exception should be throwed if driver type is not mysql.');
        } catch ( Exception $e ){}
        
        /* Use the database normally. */
        $config = array (
          'dsn' => 'mysql:host=localhost;dbname=test',
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
        );
        $database = new Database($config);
        
        /* Execute the query. */
        $result = $database->query('SHOW DATABASES');
        foreach ( $result as $database ) {
            if ( 'test' === $database['Database'] ) {
                return true;
            }
        }
        $this->fail('test database does not exists.');
    }
}