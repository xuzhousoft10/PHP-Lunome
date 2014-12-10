<?php
/** 
 * Migration file for create_db_table_configurations 
 */
namespace X\Module\Lunome\Migration;

/**
 * 
 */
use X\Service\XDatabase\Core\Table\Manager;
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;

/** 
 * M00021_create_db_table_configurations 
 */
class M00021_create_db_table_configurations extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('name')->setType(ColumnType::T_VARCHAR)->setLength(128)->setNullable(false);
        $columns[] = Column::create('value')->setType(ColumnType::T_VARCHAR)->setLength(256);
        $columns[] = Column::create('updated_at')->setType(ColumnType::T_DATETIME);
        $columns[] = Column::create('updated_by')->setType(ColumnType::T_VARCHAR)->setLength(36);
        $table = Manager::create('configurations', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        Manager::open('configurations')->drop();
    }
}