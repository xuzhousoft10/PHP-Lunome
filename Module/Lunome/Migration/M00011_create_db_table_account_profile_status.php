<?php
/** 
 * Migration file for create_db_table_account_profile_status 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/** 
 * M00011_create_db_table_account_profile_status 
 */
class M00011_create_db_table_account_profile_status extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false)->setIsAutoIncrement(true);
        $columns[] = Column::create('account_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $columns[] = Column::create('relationship_status')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('in_a_relationship_with')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('education')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('is_working')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('work')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('emergency_contact')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('contact_place')->setType(ColumnType::T_VARCHAR)->setLength(45);
        $columns[] = Column::create('school_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $columns[] = Column::create('company_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $table = TableManager::create('account_profile_status', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_profile_status')->drop();
    }
}