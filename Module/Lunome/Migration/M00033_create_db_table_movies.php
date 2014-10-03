<?php
/** 
 * Migration file for create_db_table_movies 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/** 
 * M00033_create_db_table_movies 
 */
class M00033_create_db_table_movies extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false)->setIsAutoIncrement(true);
        $columns[] = Column::create('name')->setType(ColumnType::T_VARCHAR)->setLength(256)->setNullable(false);
        $columns[] = Column::create('region_id')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('length')->setType(ColumnType::T_SMALLINT)->setIsUnsigned(true);
        $columns[] = Column::create('overview')->setType(ColumnType::T_VARCHAR)->setLength(1024);
        $columns[] = Column::create('is_adult')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('language_id')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('released_at')->setType(ColumnType::T_DATE);
        $columns[] = Column::create('directors_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $columns[] = Column::create('budget')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('color')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('production_company_id')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('box_office')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $table = TableManager::create('movies', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('movies')->drop();
    }
}