<?php
/** 
 * Migration file for create_db_table_regions 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00004_create_db_table_regions 
 */
class M00004_create_db_table_system_regions extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->varchar(13)->notNull();
        $columns[] = Column::create('name')->varchar(128)->notNull();
        $columns[] = Column::create('level')->int(Column::T_TINYINT)->notNull();
        $columns[] = Column::create('parent')->varchar(13)->notNull();
        $table = Manager::create('system_regions', $columns);
        
        $table->addPrimaryKey('id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        Manager::open('system_regions')->drop();
    }
}