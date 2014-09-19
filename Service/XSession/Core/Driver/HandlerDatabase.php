<?php
/**
 * Namespace defination.
 */
namespace X\Service\XSession\Core\Driver;

/**
 * The session handler to store session data in database.
 * Session table 
 * id       varchar(32) NOT NULL
 * access   int(10) unsigned DEFAULT NULL,
 * data     text,
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class HandlerDatabase extends  \X\Service\XSession\Core\HandlerDriverBasic {
    /**
     * This value hold the pdo object to database
     * @var \PDO
     */
    protected $pdo = null;
    
    /**
     * This value contains the name of session table.
     * @var string
     */
    protected $tableName = null;
    
    /**
     * Initiate the session handler.
     * 
     * @param array $connectionInfo
     */
    public function __construct( $connectionInfo ) {
        try {
            if ( isset($connectionInfo['user']) ) {
                $this->pdo = new \PDO($connectionInfo['dsn'], $connectionInfo['user'], $connectionInfo['password']);
            } else {
                $this->pdo = new \PDO($connectionInfo['dsn']);
            }
            $this->tableName = $connectionInfo['table'];
        } catch ( \PDOException $e ) {
            $this->hasError = true;
            error_log($e->getMessage());
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see SessionHandlerInterface::open()
     */
    public function open($save_path, $sessionid) {
        return !(is_null($this->pdo));
    }

    /**
     * (non-PHPdoc)
     * @see SessionHandlerInterface::close()
     */
    public function close() {
        return true;
    }

    /**
     * (non-PHPdoc)
     * @see SessionHandlerInterface::read()
     */
    public function read($sessionid) {
        $query = sprintf('SELECT data FROM `%s` WHERE id = :id', $this->tableName);
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':id', $sessionid);
        
        if ( $statement->execute() ) {
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return isset($results[0]) ? $results[0]['data'] : '';
        } else {
            return '';
        }
    }

    /**
     * (non-PHPdoc)
     * @see SessionHandlerInterface::write()
     */
    public function write($sessionid, $sessiondata) {
        $access = time();
        
        $query = sprintf('REPLACE INTO `%s` VALUES (:id, :access, :data)', $this->tableName);
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':id', $sessionid);
        $statement->bindParam(':access', $access);
        $statement->bindParam(':data', $sessiondata);
        
        return $statement->execute();
    }

    /**
     * (non-PHPdoc)
     * @see SessionHandlerInterface::destroy()
     */
    public function destroy($sessionid) {
        $query = sprintf('DELETE FROM `%s` WHERE id = :id', $this->tableName);
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':id', $sessionid);
        
        return $statement->execute();
    }

    /**
     * (non-PHPdoc)
     * @see SessionHandlerInterface::gc()
     */
    public function gc($maxlifetime) {
        $old = time() - $maxlifetime;
        
        $query = sprintf('DELETE FROM `%s` WHERE access < :old', $this->tableName);
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':old', $old);
        
        return $statement->execute(); 
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XSession\XSessionHandlerDriverBase::checkConfig()
     */
    public function checkConfig($config) {
        if ( $this->hasError() ) {
            return false;
        }
        
        $result = $this->pdo->query(sprintf('SELECT * FROM `%s`', $this->tableName));
        return !(false === $result);
    }
}