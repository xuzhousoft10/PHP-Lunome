<?php
/** 
 * Migration file for create_db_table_tvs 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/** 
 * M00008_create_db_table_tvs 
 */
class M00008_create_db_table_tvs extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('name')->setType(ColumnType::T_VARCHAR)->setLength(128)->setNullable(false);
        $table = TableManager::create('tvs', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('tvs')->drop();
    }
}