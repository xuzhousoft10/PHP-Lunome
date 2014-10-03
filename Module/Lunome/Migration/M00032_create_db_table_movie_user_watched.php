<?php
/** 
 * Migration file for create_db_table_movie_user_watched 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00032_create_db_table_movie_user_watched 
 */
class M00032_create_db_table_movie_user_watched extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('account_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('movie_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('rate')->setType(Column::T_TINYINT)->unsigned();
        $table = TableManager::create('movie_user_watched', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('movie_user_watched')->drop();
    }
}