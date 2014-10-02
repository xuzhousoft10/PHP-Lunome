<?php
/** 
 * Migration file for create_db_table_user_data_contact_types 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00016_create_db_table_user_data_contact_types 
 */
class M00016_create_db_table_user_data_contact_types extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('name')->varchar(45)->notNull();
        $table = TableManager::create('user_data_contact_types', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('user_data_contact_types')->drop();
    }
}