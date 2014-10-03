<?php
/** 
 * Migration file for create_db_table_account_profile_status 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00011_create_db_table_account_profile_status 
 */
class M00011_create_db_table_account_profile_status extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('account_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('relationship_status')->setType(Column::T_TINYINT)->unsigned();
        $columns[] = Column::create('in_a_relationship_with')->int()->unsigned();
        $columns[] = Column::create('education')->setType(Column::T_TINYINT)->unsigned();
        $columns[] = Column::create('is_working')->setType(Column::T_TINYINT)->unsigned();
        $columns[] = Column::create('work')->int()->unsigned();
        $columns[] = Column::create('emergency_contact')->int()->unsigned();
        $columns[] = Column::create('contact_place')->varchar(45);
        $columns[] = Column::create('school_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('company_id')->int()->unsigned()->notNull();
        $table = TableManager::create('account_profile_status', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_profile_status')->drop();
    }
}