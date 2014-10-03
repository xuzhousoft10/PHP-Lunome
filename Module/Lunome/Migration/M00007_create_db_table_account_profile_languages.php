<?php
/** 
 * Migration file for create_db_table_account_profile_languages 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00007_create_db_table_account_profile_languages 
 */
class M00007_create_db_table_account_profile_languages extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('account_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('language_id')->int()->unsigned()->notNull();
        $table = TableManager::create('account_profile_languages', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_profile_languages')->drop();
    }
}