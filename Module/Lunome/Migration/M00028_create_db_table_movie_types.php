<?php
/** 
 * Migration file for create_db_table_movie_types 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00028_create_db_table_movie_types 
 */
class M00028_create_db_table_movie_types extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('movie_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('type_id')->int()->unsigned()->notNull();
        $table = TableManager::create('movie_types', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('movie_types')->drop();
    }
}