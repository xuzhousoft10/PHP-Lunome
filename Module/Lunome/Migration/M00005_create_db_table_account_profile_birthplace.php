<?php
/** 
 * Migration file for create_db_table_account_profile_birthplace 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00005_create_db_table_account_profile_birthplace 
 */
class M00005_create_db_table_account_profile_birthplace extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->varchar(13)->notNull();
        $columns[] = Column::create('account')->varchar(13)->notNull();
        $columns[] = Column::create('region_id')->varchar(13)->notNull();
        $columns[] = Column::create('level')->int(Column::T_TINYINT)->notNull();
        $table = Manager::create('account_profile_birthplace', $columns);
        $table->addPrimaryKey('id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        Manager::open('account_profile_birthplace')->drop();
    }
}