<?php
/** 
 * Migration file for create_db_table_book_user_read 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00018_create_db_table_book_user_read 
 */
class M00018_create_db_table_book_user_read extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('account_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('book_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('rate')->setType(Column::T_TINYINT)->unsigned();
        $table = TableManager::create('book_user_read', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('book_user_read')->drop();
    }
}