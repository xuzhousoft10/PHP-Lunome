<?php
/** 
 * Migration file for create_db_table_account_profile_education_history 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/** 
 * M00006_create_db_table_account_profile_education_history 
 */
class M00006_create_db_table_account_profile_education_history extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false)->setIsAutoIncrement(true);
        $columns[] = Column::create('account_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $columns[] = Column::create('started_at')->setType(ColumnType::T_DATE);
        $columns[] = Column::create('ended_at')->setType(ColumnType::T_DATE);
        $columns[] = Column::create('school_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $table = TableManager::create('account_profile_education_history', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_profile_education_history')->drop();
    }
}