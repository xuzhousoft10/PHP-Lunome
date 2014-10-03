<?php
/** 
 * Migration file for create_db_table_accounts 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00016_create_db_table_accounts 
 */
class M00016_create_db_table_accounts extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('status')->setType(Column::T_TINYINT)->unsigned()->notNull()->defaultVal('0');
        $columns[] = Column::create('enabled_at')->datetime();
        $table = TableManager::create('accounts', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('accounts')->drop();
    }
}