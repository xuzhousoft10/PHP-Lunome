<?php
/** 
 * Migration file for alter_db_table_movies_add_has_cover 
 */
namespace X\Module\Lunome\Migration;

/**
 * 
 */
use X\Service\XDatabase\Core\Table\Manager;
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;

/** 
 * M00019_alter_db_table_movies_add_has_cover 
 */
class M00019_alter_db_table_movies_add_has_cover extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $column = Column::create('has_cover')->setType(ColumnType::T_TINYINT)->setDefault(0);
        Manager::open('media_movies')->addColumn($column);
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        Manager::open('media_movies')->dropColumn('has_cover');
    }
}