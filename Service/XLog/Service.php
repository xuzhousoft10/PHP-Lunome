<?php
/**
 * This file implements the service Movie
 */
namespace X\Service\XLog;

/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
    /**
     * @var unknown
     */
    protected static $serviceName = 'XLog';
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::start()
     */
    public function start() {
        parent::start();
        
        require_once $this->getPath('Core/Log4PHP/Logger.php');
        $configuration = $this->getConfiguration()->toArray();
        \Logger::configure($configuration['log4php']);
        $this->logger = \Logger::getRootLogger();
    }
    
    /**
     * @var \LoggerRoot
     */
    private $logger = null;
    
    /**
     * @param string $name
     * @return \Logger
     */
    public function getLogger( $name ) {
        return $this->logger->getLogger($name);
    }
    
    public function getLogs($position, $limit) {
        $logs = array();
        $config = $this->getConfiguration()->get('log4php');
        $appander = $config['appenders']['default'];
        switch ( $appander['class'] ) {
        case 'LoggerAppenderPDO':
            $logs = $this->readFromFromPDO($appander['params'], $position, $limit);
            break;
        default:break;
        }
        return $logs;
    }
    
    private function readFromFromPDO( $params, $position, $limit ) {
        $position = (int)$position;
        $limit = (int)$limit;
        $pdo = new \PDO($params['dsn'], $params['user'], $params['password']);
        $query = 'SELECT * FROM '.$params['table'].' ORDER BY time DESC LIMIT '.$limit.' OFFSET '.$position;
        $result = $pdo->query($query);
        $result = $result->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
}