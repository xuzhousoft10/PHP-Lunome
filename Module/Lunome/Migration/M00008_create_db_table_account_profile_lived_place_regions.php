<?php
/** 
 * Migration file for create_db_table_account_profile_lived_place_regions 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00008_create_db_table_account_profile_lived_place_regions 
 */
class M00008_create_db_table_account_profile_lived_place_regions extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('region_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('lived_place_id')->int()->notNull();
        $table = TableManager::create('account_profile_lived_place_regions', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_profile_lived_place_regions')->drop();
    }
}