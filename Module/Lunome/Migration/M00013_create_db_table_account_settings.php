<?php
/** 
 * Migration file for create_db_table_account_settings 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00013_create_db_table_account_settings 
 */
class M00013_create_db_table_account_settings extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('account_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('language')->int()->unsigned();
        $columns[] = Column::create('timezone')->setType(Column::T_TINYINT);
        $columns[] = Column::create('theme')->setType(Column::T_SMALLINT)->unsigned();
        $columns[] = Column::create('header_image')->int()->unsigned();
        $columns[] = Column::create('background_image')->int()->unsigned();
        $columns[] = Column::create('notification_new_movie')->setType(Column::T_TINYINT)->unsigned();
        $columns[] = Column::create('notification_new_tv')->setType(Column::T_TINYINT)->unsigned();
        $columns[] = Column::create('notification_new_book')->setType(Column::T_TINYINT)->unsigned();
        $table = TableManager::create('account_settings', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_settings')->drop();
    }
}