<?php
/** 
 * Migration file for create_db_table_book_types 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00018_create_db_table_book_types 
 */
class M00018_create_db_table_book_types extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull();
        $columns[] = Column::create('book_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('type_id')->int()->unsigned()->notNull();
        $table = TableManager::create('book_types', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('book_types')->drop();
    }
}