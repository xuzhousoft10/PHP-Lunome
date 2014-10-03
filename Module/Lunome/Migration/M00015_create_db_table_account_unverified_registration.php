<?php
/** 
 * Migration file for create_db_table_account_unverified_registration 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00015_create_db_table_account_unverified_registration 
 */
class M00015_create_db_table_account_unverified_registration extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('email')->varchar(128)->notNull();
        $columns[] = Column::create('password')->varchar(128)->notNull();
        $columns[] = Column::create('registered_at')->datetime()->notNull();
        $columns[] = Column::create('expired_at')->datetime()->notNull();
        $table = TableManager::create('account_unverified_registration', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_unverified_registration')->drop();
    }
}