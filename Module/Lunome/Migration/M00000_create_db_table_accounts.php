<?php
/** 
 * Migration file for create_db_table_accounts 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/** 
 * M00000_create_db_table_accounts 
 */
class M00000_create_db_table_accounts extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('account')->setType(ColumnType::T_INT)->setNullable(false);
        $columns[] = Column::create('oauth20_id')->setType(ColumnType::T_VARCHAR)->setLength(36);
        $columns[] = Column::create('nickname')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $columns[] = Column::create('status')->setType(ColumnType::T_TINYINT)->setNullable(false);
        $columns[] = Column::create('enabled_at')->setType(ColumnType::T_DATETIME);
        $columns[] = Column::create('photo')->setType(ColumnType::T_VARCHAR)->setLength(256);
        $table = TableManager::create('accounts', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('accounts')->drop();
    }
}