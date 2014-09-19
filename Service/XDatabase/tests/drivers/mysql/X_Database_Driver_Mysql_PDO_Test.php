<?php
/**
 * X_Database_Driver_Mysql_PDO_Test.php
 * @author  Michael Luthor <michael.the.ranidae@tgmail.com>
 * @version 0.0.0
 */
use X\Database\Driver\Mysql\PDO;
/**
 * PDO test case.
 */
class X_Database_Driver_Mysql_PDO_Test extends PHPUnit_Framework_TestCase {
    /**
     * 
     * @var unknown
     */
    protected $config = array(
            'dsn'       =>  TEST_MYSQL_DB_DSN, 
            'username'  =>  TEST_MYSQL_DB_USERNAME, 
            'password'  =>  TEST_MYSQL_DB_PASSWORD);
    
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
     * Tests PDO->__construct()
     */
    public function test__construct() {
        $driver = new PDO($this->config);
        $this->assertInstanceOf('X\Database\Driver\Mysql\PDO', $driver);
    }
    
    /**
     * Tests PDO->exec()
     */
    public function testExec() {
        $driver = new PDO($this->config);
        $this->assertTrue($driver->exec('SELECT 1+1'));
        $this->assertFalse($driver->exec('XXXXXX'));
    }
    
    /**
     * Tests PDO->query()
     */
    public function testQuery() {
        $driver = new PDO($this->config);
        $result = $driver->query('SELECT 1+1');
        $this->assertEquals(2, $result[0]['1+1']);
        $this->assertFalse($driver->query('XXXX'));
    }
    
    /**
     * Tests PDO->quote()
     */
    public function testQuote() {
        $driver = new PDO($this->config);
        $expected = "'str\'ing'";
        $actual = $driver->quote("str'ing");
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests PDO->lastInsertId()
     */
    public function testLastInsertId() {
        $driver = new PDO($this->config);
        $driver->exec("TRUNCATE active_record_tester");
        $driver->exec("INSERT INTO active_record_tester VALUES ('', 1)");
        $this->assertEquals(1, $driver->lastInsertId());
    }
    
    /**
     * Tests PDO->quoteTableName()
     */
    public function testQuoteTableName() {
        $driver = new PDO($this->config);
        $expected = '`table`';
        $actual = $driver->quoteTableName('table');
        $this->assertEquals($expected, $actual);
    }
}

