<?php
/** 
 * Migration file for create_db_table_system_data_working_positions 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00040_create_db_table_system_data_working_positions 
 */
class M00040_create_db_table_system_data_working_positions extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('name')->varchar(45)->notNull();
        $table = TableManager::create('system_data_working_positions', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('system_data_working_positions')->drop();
    }
}