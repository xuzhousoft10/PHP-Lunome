<?php
/** 
 * Migration file for create_db_table_account_profile_basic 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00001_create_db_table_account_profile_basic 
 */
class M00001_create_db_table_account_profile_basic extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('account_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('nickname')->varchar(32)->notNull();
        $columns[] = Column::create('photo')->int()->unsigned();
        $columns[] = Column::create('realname')->varchar(64);
        $columns[] = Column::create('sex')->setType(Column::T_TINYINT);
        $columns[] = Column::create('birthday')->date();
        $columns[] = Column::create('nationality')->int()->unsigned();
        $columns[] = Column::create('blood_type')->setType(Column::T_TINYINT)->unsigned();
        $columns[] = Column::create('sexual_orientation')->setType(Column::T_TINYINT)->unsigned();
        $columns[] = Column::create('religiou_id')->int()->unsigned()->notNull();
        $table = TableManager::create('account_profile_basic', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_profile_basic')->drop();
    }
}