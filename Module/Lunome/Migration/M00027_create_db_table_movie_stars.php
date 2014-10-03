<?php
/** 
 * Migration file for create_db_table_movie_stars 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00027_create_db_table_movie_stars 
 */
class M00027_create_db_table_movie_stars extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->notNull();
        $columns[] = Column::create('movie_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('actor_id')->int()->unsigned()->notNull();
        $table = TableManager::create('movie_stars', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('movie_stars')->drop();
    }
}