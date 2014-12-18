<?php
/**
 * PDO.php
 */
namespace X\Service\XDatabase\Core\Driver\Mysql;

/**
 * Use statements
 */
use X\Core\X;
use X\Service\XDatabase\Core\Basic;
use X\Service\XDatabase\Core\Exception;
use X\Service\XDatabase\Core\Driver\InterfaceDriver;
use X\Service\XLog\Service as XLogService;

/**
 * PDO
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class PDO extends Basic implements InterfaceDriver {
    /**
     * The PDO object for current connection.
     * 
     * @var \PDO
     */
    protected $connection = null;
    
    /**
     * Initiate current driver PDO object by given config information.
     * 
     * @param array $config
     */
    public function __construct( $config ) {
        $this->connection = new \PDO($config['dsn'], $config['username'], $config['password']);
        $this->exec(sprintf('SET NAMES %s', $config['charset']));
    }
    
    /**
     * Execute the query, and return true on successed and false if failed.
     * 
     * @param string $query The query to execute.
     * @return boolean
     */
    public function exec( $query ) {
        $timeStarted = microtime(true);
        $this->connection->exec($query);
        $this->recordQuery($timeStarted, microtime(true), $query);
        $errorCode = $this->connection->errorCode();
        if ( '00000' !== $errorCode ) {
            throw new Exception($this->getErrorMessage());
        }
    }
    
    /**
     * Execute the query and return the result of query on successed 
     * and false if failed.
     * 
     * @param string $query
     * @return boolean|array
     */
    public function query( $query ) {
        $timeStart = microtime(true);
        $result = $this->connection->query($query);
        $this->recordQuery($timeStart, microtime(true), $query);
        if ( false === $result ) {
            throw new Exception($this->getErrorMessage());
        }
        
        $result = $result->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
    
    /**
     * Quote the string for safety using in query.
     * 
     * @param string $string The value to quote.
     * @return string
     */
    public function quote($string) {
        return $this->connection->quote($string);
    }
    
    /**
     * Get the last insert id after execute a insert query.
     * 
     * @return integer
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    /**
     * Quote the name of table for safty using in query string.
     * 
     * @param string $name The name to quoted.
     * @return string
     */
    public function quoteTableName($name) {
        return sprintf('`%s`', $name);
    }
    
    /**
     * @param unknown $name
     * @return string
     */
    public function quoteColumnName( $name ) {
        return "`$name`";
    }
    
    /**
     * Get the name list of table.
     * 
     * @return array
     */
    public function getTables() {
        $tables = array();
        $result = $this->query('SHOW TABLES');
        foreach ( $result as $table ) {
            $table = array_values($table);
            array_push($tables, $table[0]);
        }
        return $tables;
    }
    
    /**
     * Returns an string of error information about the last operation performed by this database handle
     * 
     * @return string
     */
    private function getErrorMessage() {
        $error = $this->connection->errorInfo();
        return isset($error[2]) ? $error[2] : '';
    }
    
    /**
     * @var XLogService
     */
    private static $logger = null;
    
    /**
     * @param unknown $timeSpend
     * @param unknown $query
     */
    private function recordQuery( $timeStarted, $timeEnded, $query) {
        if ( null === self::$logger ) {
            self::$logger = X::system()->getServiceManager()->get(XLogService::getServiceName());
        }
        $timeSpend = bcsub($timeEnded, $timeStarted, 4);
        $message = '['.$timeSpend.'s]     '.$query;
        self::$logger->getLogger('XDATABASE_DRIVER')->debug($message);
    }
}