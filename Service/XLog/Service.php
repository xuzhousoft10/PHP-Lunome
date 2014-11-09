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
        \Logger::configure($this->getConfiguration());
        $this->logger = \Logger::getRootLogger();
    }
    
    /**
     * @var \LoggerRoot
     */
    private $logger = null;
}