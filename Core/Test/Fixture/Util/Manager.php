<?php
namespace X\Core\Test\Fixture\Util;

/**
 * 
 */
use X\Core\Util\Manager as ManagerFixture;

/**
 *
 */
class Manager extends ManagerFixture {
    /**
     * @var unknown
     */
    static public $initedCount = 0;
    
    /**
     * @var boolean
     */
    public $isInited = false;
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\Manager::init()
     */
    protected function init() {
        parent::init();
        $this->isInited = true;
        self::$initedCount ++;
    }
    
    /**
     * @var unknown
     */
    public $configurationPath = false;
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\Manager::getConfigurationFilePath()
     */
    protected function getConfigurationFilePath() {
        if ( false === $this->configurationPath ) {
            return parent::getConfigurationFilePath();
        } else {
            return $this->configurationPath;
        }
    }
}