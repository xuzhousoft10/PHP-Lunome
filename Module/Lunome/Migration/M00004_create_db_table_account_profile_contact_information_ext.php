<?php
/** 
 * Migration file for create_db_table_account_profile_contact_information_ext 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00004_create_db_table_account_profile_contact_information_ext 
 */
class M00004_create_db_table_account_profile_contact_information_ext extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('account_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('account')->varchar(45)->notNull();
        $columns[] = Column::create('type_id')->int()->unsigned()->notNull();
        $table = TableManager::create('account_profile_contact_information_ext', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_profile_contact_information_ext')->drop();
    }
}