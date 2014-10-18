<?php
/** 
 * Migration file for create_db_table_media_user_marks 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/** 
 * M00005_create_db_table_media_user_marks 
 */
class M00005_create_db_table_media_user_marks extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('media_type')->setType(ColumnType::T_VARCHAR)->setLength(128)->setNullable(false);
        $columns[] = Column::create('media_id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('account_id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('mark')->setType(ColumnType::T_TINYINT)->setNullable(false);
        $table = TableManager::create('media_user_marks', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('media_user_marks')->drop();
    }
}