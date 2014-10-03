<?php
/** 
 * Migration file for create_db_table_movie_user_notes 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00030_create_db_table_movie_user_notes 
 */
class M00030_create_db_table_movie_user_notes extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('account_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('movie_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('language_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('content')->varchar(512)->notNull();
        $columns[] = Column::create('wrote_at')->date()->notNull();
        $table = TableManager::create('movie_user_notes', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('movie_user_notes')->drop();
    }
}