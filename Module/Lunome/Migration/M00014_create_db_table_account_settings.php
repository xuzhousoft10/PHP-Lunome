<?php
/** 
 * Migration file for create_db_table_account_settings 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/** 
 * M00014_create_db_table_account_settings 
 */
class M00014_create_db_table_account_settings extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false)->setIsAutoIncrement(true);
        $columns[] = Column::create('account_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $columns[] = Column::create('language')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('timezone')->setType(ColumnType::T_TINYINT);
        $columns[] = Column::create('theme')->setType(ColumnType::T_SMALLINT)->setIsUnsigned(true);
        $columns[] = Column::create('header_image')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('background_image')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('notification_new_movie')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('notification_new_tv')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('notification_new_book')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
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