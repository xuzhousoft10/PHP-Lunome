<?php
/** 
 * Migration file for create_db_table_media_posters 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/** 
 * M00004_create_db_table_media_posters 
 */
class M00004_create_db_table_media_posters extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('media_type')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('media_id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('data')->setType(ColumnType::T_LONGTEXT);
        $table = TableManager::create('media_posters', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('media_posters')->drop();
    }
}