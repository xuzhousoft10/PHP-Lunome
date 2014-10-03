<?php
/** 
 * Migration file for create_db_table_tv_stars 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00045_create_db_table_tv_stars 
 */
class M00045_create_db_table_tv_stars extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('tv_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('actor_id')->int()->unsigned()->notNull();
        $table = TableManager::create('tv_stars', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('tv_stars')->drop();
    }
}