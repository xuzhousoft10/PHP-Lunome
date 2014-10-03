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
 * M00050_create_db_table_tvs 
 */
class M00050_create_db_table_tvs extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false)->setIsAutoIncrement(true);
        $columns[] = Column::create('name')->setType(ColumnType::T_VARCHAR)->setLength(256)->setNullable(false);
        $columns[] = Column::create('production_company_id')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('region_id')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('episode_count')->setType(ColumnType::T_SMALLINT)->setIsUnsigned(true);
        $columns[] = Column::create('episode_lenght')->setType(ColumnType::T_SMALLINT)->setIsUnsigned(true);
        $columns[] = Column::create('season_count')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('language_id')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('color')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
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