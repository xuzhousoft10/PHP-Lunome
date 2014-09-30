<?php
/** 
 * Migration file for create_db_table_account_settings 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/** 
 * M00002_create_db_table_account_settings 
 */
class M00002_create_db_table_account_settings extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->varchar(13)->notNull();
        $columns[] = Column::create('account')->varchar(13)->notNull();
        $columns[] = Column::create('language')->int(Column::T_TINYINT)->unsigned()->defaultVal(0);
        $columns[] = Column::create('timezone')->int(Column::T_TINYINT)->defaultVal(0);
        $columns[] = Column::create('theme')->int(Column::T_TINYINT)->defaultVal(0);
        $columns[] = Column::create('header_image')->varchar(13);
        $columns[] = Column::create('background-image')->varchar(13);
        $columns[] = Column::create('notification_new_movie')->int(Column::T_TINYINT)->defaultVal(0);
        $columns[] = Column::create('notification_new_tv')->int(Column::T_TINYINT)->defaultVal(0);
        $columns[] = Column::create('notification_new_book')->int(Column::T_TINYINT)->defaultVal(0);
        $table = TableManager::create('account_settings', $columns);
        
        $table->addPrimaryKey('id');
        $table->addUnique('id');
        $table->addUnique('account');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_settings')->drop();
    }
}