<?php
/** 
 * Migration file for create_db_table_account_sercurity 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statement
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/** 
 * M00001_create_db_table_account_sercurity 
 */
class M00001_create_db_table_account_sercurity extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->varchar(13)->notNull();
        $columns[] = Column::create('account')->varchar(13)->notNull();
        $columns[] = Column::create('password')->varchar(64)->notNull();
        $table = TableManager::create('account_security', $columns);
        
        $table->addPrimaryKey('id');
        $table->addUnique('id');
        $table->addUnique('account');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_security')->drop();
    }
}