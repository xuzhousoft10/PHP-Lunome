<?php
/** 
 * Migration file for create_db_table_account_unverified_registration 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/** 
 * M00015_create_db_table_account_unverified_registration 
 */
class M00015_create_db_table_account_unverified_registration extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false)->setIsAutoIncrement(true);
        $columns[] = Column::create('email')->setType(ColumnType::T_VARCHAR)->setLength(128)->setNullable(false);
        $columns[] = Column::create('password')->setType(ColumnType::T_VARCHAR)->setLength(128)->setNullable(false);
        $columns[] = Column::create('registered_at')->setType(ColumnType::T_DATETIME)->setNullable(false);
        $columns[] = Column::create('expired_at')->setType(ColumnType::T_DATETIME)->setNullable(false);
        $table = TableManager::create('account_unverified_registration', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_unverified_registration')->drop();
    }
}