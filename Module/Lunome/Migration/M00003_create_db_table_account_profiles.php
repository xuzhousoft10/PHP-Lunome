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
        /* @TODO: About birthplace, how to save them as int. */
        
        TableManager::create('account_profiles', $columns);
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        /*@TODO: Add your migration code here. */
    }
}