<?php
/** 
 * Migration file for create_db_table_account_login_history 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/** 
 * M00011_create_db_table_account_login_history 
 */
class M00011_create_db_table_account_login_history extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('account_id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('time')->setType(ColumnType::T_DATETIME)->setNullable(false);
        $columns[] = Column::create('ip')->setType(ColumnType::T_VARCHAR)->setLength(64)->setNullable(false);
        $columns[] = Column::create('logined_by')->setType(ColumnType::T_VARCHAR)->setLength(32)->setNullable(false);
        $columns[] = Column::create('country')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $columns[] = Column::create('province')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $columns[] = Column::create('city')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $columns[] = Column::create('isp')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $table = TableManager::create('account_login_history', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_login_history')->drop();
    }
}