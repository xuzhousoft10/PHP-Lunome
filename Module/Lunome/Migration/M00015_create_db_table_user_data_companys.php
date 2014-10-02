<?php
/** 
 * Migration file for create_db_table_user_data_companys 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00015_create_db_table_user_data_companys 
 */
class M00015_create_db_table_user_data_companys extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('name')->varchar(256)->notNull();
        $columns[] = Column::create('working_history_id')->int()->notNull();
        $table = TableManager::create('user_data_companys', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('user_data_companys')->drop();
    }
}