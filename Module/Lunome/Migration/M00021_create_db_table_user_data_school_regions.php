<?php
/** 
 * Migration file for create_db_table_user_data_school_regions 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00021_create_db_table_user_data_school_regions 
 */
class M00021_create_db_table_user_data_school_regions extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('school_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('region_id')->int()->unsigned()->notNull();
        $table = TableManager::create('user_data_school_regions', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('user_data_school_regions')->drop();
    }
}