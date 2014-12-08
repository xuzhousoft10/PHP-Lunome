<?php
/**
 * database.php
 */
namespace X\Service\XDatabase\Core;

/**
 * Database
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 * @method array query( $query ) Executes an SQL statement, returning an array as result set.
 * @method boolean exec( $query ) Executes an SQL statement, returning true or false.
 * @method integer lastInertId() Returns the ID of the last inserted row or sequence value.
 * @method string quoteColumnName($name)
 * @method string quoteTableName($name)
 */
class Database extends Basic {
    /**
     * Initiate the database by given config information.
     *
     * @param array $config The config information to initiate the database.
     */
    public function __construct( $config ) {
        $this->config = $config;
    }
    
    /**
     * Do magic call
     * 
     * @param string $name The name of method to call
     * @param array $parms The parmas for the method
     */
    public function __call( $name, $parms ) {
        return call_user_func_array(array($this->getDriver(), $name), $parms);
    }
    
    /**
     * The config about the db.
     * @var array
     */
    protected $config = array();
    
    /**
     * The driver that current database object is using.
     * @var \X\Database\Driver\Driver
     */
    protected $driver = null;
    
    /**
     * Get driver for current Database object.
     *
     * @return \X\Database\Driver\Driver
     */
    protected function getDriver() {
        if ( null === $this->driver ) {
            $this->driver = $this->getDriverByConfig();
        }
        return $this->driver;
    }
    
    /**
     * Get The current for current Database object by config.
     * @return \X\Database\Driver\Driver
     */
    protected function getDriverByConfig() {
        $driverName = null;
        $driverHandler = null;
        if ( isset($this->config['driver']) ) {
            list($driverName, $driverHandler) = explode('.', $this->config['driver']);
        }
        else if ( isset($this->config['dsn']) ) {
            $information = explode(':', $this->config['dsn']);
            $driverName = ucfirst($information[0]);
            $driverHandler = 'PDO';
        }
        else {
            throw new Exception('Can not find driver from config.');
        }
        
        $driverClass = sprintf('%s\\Driver\\%s\\%s', __NAMESPACE__, $driverName, $driverHandler);
        return new $driverClass($this->config);
    }
    
    /**
     * Get config value from current database configs.
     * 
     * @param string $name The name of config option.
     * @return mixed
     */
    private function getConfig( $name ) {
        return $this->config[ $name ];
    }
}