<?php
/** 
 * Migration file for create_db_table_account_profile_working_history 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00011_create_db_table_account_profile_working_history 
 */
class M00011_create_db_table_account_profile_working_history extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('account_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('started_at')->date();
        $columns[] = Column::create('ended_at')->date();
        $columns[] = Column::create('positions_id')->int()->unsigned()->notNull();
        $table = TableManager::create('account_profile_working_history', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_profile_working_history')->drop();
    }
}