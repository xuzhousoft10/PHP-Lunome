<?php
/**
 * Namespace defination
 */
namespace X\Service\XRequest\Core\Recorder;

/**
 * The requestion recorder of database handler.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class RequestRecorderDatabaseHandler extends \X\Service\XRequest\RequestRecorderBasic {
    /**
     * This value holds the connection object to database.
     * 
     * @var \PDO
     */
    protected $pdo = null;
    
    /**
     * This value holds the name of table to store the history.
     * 
     * @var string
     */
    protected $table = null;
    
    /**
     * Whether it contains error or not.
     * 
     * @var boolean
     */
    public $hasError = false;
    
    /**
     * Init this handler driver object.
     * 
     * @param array $config
     */
    public function __construct( $config ) {
        if ( !isset($config['dsn']) ) {
            $this->hasError = true;
            return;
        }
        
        $dsn = $config['dsn'];
        list($type, $info) = explode(':', $dsn);
        $handler = sprintf('init%sHandler', $type);
        if ( method_exists($this, $handler) ) {
            call_user_func_array(array($this, $handler), $config);
        } else {
            $this->initDefaultHandler($config);
        }
        
        $this->table = $config['table'];
    }
    
    /**
     * Init this default pdo object.
     * 
     * @param array $config
     */
    protected function initDefaultHandler( $config ) {
        try {
            $this->pdo = new \PDO($config['dsn'], $config['username'], $config['password']);
        } catch ( \PDOException $e ) {
            $this->hasError = true;
        }
    }
    
    /**
     * Setup this handler driver
     */
    public function setup() {
        $query = sprintf('SELECT COUNT(*) FROM `%s`', $this->table);
        $statement = $this->pdo->prepare($query);
        if ( false !== $statement->execute() ) {
            return true;
        }
        
        $query = sprintf(
                'CREATE  TABLE `%s` ('.
                    '`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,'.
                    '`url` VARCHAR(256) NOT NULL ,'.
                    '`ip` VARCHAR(128) NOT NULL ,'.
                    '`location` VARCHAR(64) NOT NULL ,'.
                    '`date` DATETIME NOT NULL ,'.
                    '`time` INT UNSIGNED NOT NULL ,'.
                'PRIMARY KEY (`id`) ,'.
                'UNIQUE INDEX `id_UNIQUE` (`id` ASC) );',
                $this->table);
        $statement = $this->pdo->prepare($query);
        return $statement->execute();
    }
    
    /**
     * Save the request into history storage.
     * 
     * @param array $request
     * @return boolean
     */
    public function record( $request ) {
        $query = sprintf('INSERT INTO `%s` VALUES (\'\', :url, :ip, :location, :date, :time )', $this->table);
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':url', $request['url']);
        $statement->bindValue(':ip', $request['ip']);
        $statement->bindValue(':location', $request['location']);
        $statement->bindValue(':date', $request['date']);
        $statement->bindValue(':time', $request['time']);
        return $statement->execute();
    }
    
    /**
     * Get history record by given parms.
     * 
     * @param array $parms
     * @return array
     */
    public function getHistory( $criteria ) {
        $query = sprintf('SELECT * FROM `%s` LIMIT %s, %s', $this->table, $criteria['pos'], $criteria['length']);
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        $histories = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $histories;
    }
}