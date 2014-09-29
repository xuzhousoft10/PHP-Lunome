<?php
/**
 * The migration interface.
 */
namespace X\Core\Module;

/**
 * 
 */
interface InterfaceMigrate {
    /**
     * Upgrade the module.
     * @return void
     */
    public function up();
    
    /**
     * degrade tge module.
     * @return void
     */
    public function down();
}