<?php
namespace X\Module\Admin;

use X\Module\Admin\Util\Commander;
class Module extends \X\Core\Module\XModule {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::run()
     */
    public function run($parameters = array()) {
        $this->commander->start();
    }
    
    /**
     * 
     * @var \X\Module\Admin\Util\Commander
     */
    protected $commander = null;
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::init()
     */
    protected function init() {
        parent::init();
        $this->commander = new Commander();
    }
    
    /**
     * Get the commander.
     * 
     * @return \X\Module\Admin\Util\Commander
     */
    public function getCommander() {
        return $this->commander;
    }
}