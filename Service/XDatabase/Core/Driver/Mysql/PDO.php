<?php
/**
 * PDO.php
 */
namespace X\Service\XDatabase\Core\Driver\Mysql;
use X\Service\XDatabase\Core\Basic;
use X\Service\XDatabase\Core\Driver\XDriver;
use X\Service\XDatabase\Core\Exception;
/**
 * PDO
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class PDO extends Basic implements XDriver {
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
        $this->connection->exec($query);
        $errorCode = $this->connection->errorCode();
        if ( '00000' !== $errorCode ) {
            throw new Exception(print_r($this->connection->errorInfo(), true), $errorCode);
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
        $result = $this->connection->query($query);
        if ( false === $result ) {
            return false;
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
}