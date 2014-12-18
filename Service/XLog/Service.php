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
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::afterStart()
     */
    protected function afterStart() {
        require_once $this->getPath('Core/Log4PHP/Logger.php');
        $configuration = $this->getConfiguration()->getAll();
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
}