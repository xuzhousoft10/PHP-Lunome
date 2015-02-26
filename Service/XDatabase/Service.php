<?php
/**
 * Namespace defination
 */
namespace X\Service\XDatabase;

/**
 * 
 */
use X\Service\XDatabase\Core\Database\Manager;

/**
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Service extends \X\Core\Service\XService {
    /**
     * @var string
     */
    protected static $serviceName = 'XDatabase';
    
    /**
     * @var Manager
     */
    private $dbManager = null;
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::start()
     */
    public function start() {
        parent::start();
        
        $this->dbManager = Manager::getManager();
        $this->dbManager->start();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::stop()
     */
    public function stop() {
        $this->dbManager->stop();
        $this->dbManager->destroy();
    }
    
    /**
     * @return \X\Service\XDatabase\Core\Database\Manager
     */
    public function getDatabaseManager() {
        return $this->dbManager;
    }
}