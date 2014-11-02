<?php
/**
 * 
 */
namespace X\Core\Module;

/**
 * 
 */
interface InterfaceMigrate {
    /**
     * 当迁移一个模块时， 该方法将会被执行。
     * @return void
     */
    public function up();
    
    /**
     * 当回滚迁移时， 该方法将会被执行。
     * @return void
     */
    public function down();
}