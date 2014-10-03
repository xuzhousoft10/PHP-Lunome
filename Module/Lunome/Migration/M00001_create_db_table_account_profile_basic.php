<?php
/** 
 * Migration file for create_db_table_account_profile_basic 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/** 
 * M00001_create_db_table_account_profile_basic 
 */
class M00001_create_db_table_account_profile_basic extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false)->setIsAutoIncrement(true);
        $columns[] = Column::create('account_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $columns[] = Column::create('nickname')->setType(ColumnType::T_VARCHAR)->setLength(32)->setNullable(false);
        $columns[] = Column::create('photo')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('realname')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $columns[] = Column::create('sex')->setType(ColumnType::T_TINYINT);
        $columns[] = Column::create('birthday')->setType(ColumnType::T_DATE);
        $columns[] = Column::create('nationality')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('blood_type')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('sexual_orientation')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('religiou_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $table = TableManager::create('account_profile_basic', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_profile_basic')->drop();
    }
}