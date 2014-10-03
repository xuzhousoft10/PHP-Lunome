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
 * M00016_create_db_table_accounts 
 */
class M00016_create_db_table_accounts extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false)->setIsAutoIncrement(true);
        $columns[] = Column::create('status')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true)->setNullable(false)->setDefault('0');
        $columns[] = Column::create('enabled_at')->setType(ColumnType::T_DATETIME);
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