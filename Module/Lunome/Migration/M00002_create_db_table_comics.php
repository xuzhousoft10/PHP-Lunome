<?php
/** 
 * Migration file for create_db_table_comics 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/** 
 * M00002_create_db_table_comics 
 */
class M00002_create_db_table_comics extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('name')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $table = TableManager::create('comics', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('comics')->drop();
    }
}