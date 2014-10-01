<?php
/** 
 * Migration file for create_db_table_account_profiles 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statement
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00003_create_db_table_account_profiles 
 */
class M00003_create_db_table_account_profiles extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->varchar(13)->notNull();
        $columns[] = Column::create('account')->varchar(13)->notNull();
        $columns[] = Column::create('nickname')->varchar(64)->notNull();
        $columns[] = Column::create('photo')->varchar(13);
        $columns[] = Column::create('realname')->varchar(64);
        $columns[] = Column::create('sex')->int(Column::T_TINYINT);
        $columns[] = Column::create('birthday')->date();
        $columns[] = Column::create('nationality')->int();
        $columns[] = Column::create('blood_type')->int();
        $columns[] = Column::create('sexual_orientation')->int();
        $columns[] = Column::create('religious')->int();
        $table = TableManager::create('account_profiles', $columns);
        
        $table->addPrimaryKey('id');
        $table->addUnique('id');
        $table->addUnique('account');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('account_profiles')->drop();
    }
}